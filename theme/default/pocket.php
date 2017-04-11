<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Pocket
 *
 * @package typecho-pocket
 * @link https://github.com/fengqi/typecho-pocket
 */

$options = Typecho_Widget::widget('Widget_Options');
$key = $options->plugin('Pocket')->key;
$token = $options->plugin('Pocket')->token;

if (!$key || !$token) exit('未配置或 Token 失效.');

$page = !isset($_REQUEST['page']) || $_REQUEST['page'] <= 1 ? 1 : $_REQUEST['page'];
$per = 20;
$prev_page = $page - 1 <= 0 ? 1 : $page - 1;
$next_page = $page + 1;
$offset = ($page -1) * $per;
$_tag = isset($_REQUEST['tag']) ? urldecode($_REQUEST['tag']) : '';

$now = time();
$cacheTime = 600;
$content = '';
$file = '/tmp/pocket_'.md5('_typecho_pocket_cache_'.$page.'_'.$_tag);
if (is_file($file)) {
    $content = file_get_contents($file);
    $_time = substr($content, 0, 10);
    $content = json_decode(substr($content, 10));

    if ($now > $_time) $content = '';
}

if (!$content) {
    $data = [
        'consumer_key' => $key,
        'access_token' => $token,
        'state' => 'all',
        'sort' => 'newest',
        'detailType' => 'complete',
        'count' => $per,
        'offset' => $offset,
    ];
    $_tag && $data['tag'] = $_tag;

    $ch = curl_init('https://getpocket.com/v3/get');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $content = curl_exec($ch);
    curl_close($ch);

    file_put_contents($file, ($now + $cacheTime).$content);
    $content = json_decode($content);
}

?>

<?php $this->need('header.php'); ?>

<div class="col-mb-12 col-8" id="main" role="main">
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
        </div>
    </article>

    <?php foreach($content->list as $item): ?>
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
        <h2 class="post-title" itemprop="name headline">
            <a itemtype="url" href="<?php echo $item->resolved_url ?>" target="_blank"><?php echo $item->resolved_title ?></a>
        </h2>

        <ul class="post-meta">
            <li>时间: <time datetime="<?php echo date('Y-m-d H:i:s', $item->time_added) ?>" itemprop="datePublished"><?php echo date('Y-m-d H:i:s', $item->time_added) ?></time></li>
            <?php if (isset($item->tags)): ?>
            <li>标签:<?php foreach ($item->tags as $tag): ?><a href="?tag=<?php echo $tag->tag; ?>"><?php echo $tag->tag; ?></a>&nbsp<?php endforeach; ?></li>
            <?php endif; ?>
        </ul>

        <div class="post-content" itemprop="articleBody">
            <?php echo $item->excerpt ?>
        </div>
    </article>
    <?php endforeach; ?>

    <ol class="page-navigator">
        <li class="prev"><a href="?tag=<?php echo $_tag;?>&page=<?php echo $prev_page ?>">« 前一页</a></li>
        <li class="next"><a href="?tag=<?php echo $_tag;?>&page=<?php echo $next_page ?>">后一页 »</a></li>
    </ol>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
