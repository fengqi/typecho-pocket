<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Pocket
 *
 * @package custom
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

$data = [
    'consumer_key' => $key,
    'access_token' => $token,
    'state' => 'all',
    'sort' => 'newest',
    'detailType' => 'simple',
    'count' => $per,
    'offset' => $offset,
];

$ch = curl_init('https://getpocket.com/v3/get');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$content = curl_exec($ch);
curl_close($ch);
$content = json_decode($content);
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
        </ul>

        <div class="post-content" itemprop="articleBody">
            <?php echo $item->excerpt ?>
        </div>
    </article>
    <?php endforeach; ?>

    <ol class="page-navigator">
        <li class="prev"><a href="?page=<?php echo $prev_page ?>">« 前一页</a></li>
        <li class="next"><a href="?page=<?php echo $next_page ?>">后一页 »</a></li>
    </ol>

    <?php $this->need('comments.php'); ?>
</div>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
