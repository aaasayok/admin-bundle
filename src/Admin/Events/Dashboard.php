<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/7/30
 * Time: 下午11:53
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace Admin\Events;

class Dashboard extends DashAuthorization
{
    public function indexAction()
    {
        return $this->render('Admin/Resources/views/dash/board.twig');
    }
}