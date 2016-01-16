<?php
/**
 * Pocket 插件
 *
 * @package Pocket
 * @author fengqi
 * @version 0.0.1
 * @link https://github.com/fengqi/typecho-pocket
 */
class Pocket_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addRoute('pocket-token', '/pocket/token', 'Pocket_Action', 'token');
        Helper::addRoute('pocket-token-return', '/pocket/token-return', 'Pocket_Action', 'tokenReturn');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        Helper::removeRoute('pocket-token');
        Helper::removeRoute('pocket-token-return');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $key = new Typecho_Widget_Helper_Form_Element_Text('key', NULL, NULL, _t('Consumer Key'), _t('点 <a href="https://getpocket.com/developer/apps/" target="_blank">此链接</a> 创建并应用并获得, 创建时需要选中 Retrieve 权限.'));
        $form->addInput($key->addRule('required', _t('Key 不能为空')));

        $token = new Typecho_Widget_Helper_Form_Element_Text('token', NULL, NULL, _t('Access Token'), _t('获取 Access Token 需要 Consumer Key.<br/>请先填写 Consumer Key, 然后点击保存设置.<br>再次回到此界面, 点 <a href="/pocket/token" target="_blank">此链接</a> 获得 Access Token, 允许授权, 输入页面输出的内容.'));
        $form->addInput($token);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // todo
    }

}
