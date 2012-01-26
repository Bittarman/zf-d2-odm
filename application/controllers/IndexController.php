<?php
use Lupi\Controller as controller;

class IndexController extends controller\Action
{
	/**
	 * @var \Doctrine\ODM\MongoDB\DocumentRepository
	 */
	protected $repository;

    public function init()
    {
        $this->repository = $this->dm->getRepository('Application\Blog\Post');
    }

    public function indexAction()
    {
    	$post = $this->repository->find('4dde4067fbd2237df1000000');
        $comment = new \Application\Blog\Comment();
        $comment->setEmail('foo@test.com')
                ->setComment('nice post!');
        $post->addComment($comment);
        $this->dm->persist($post);
        $this->dm->flush();
    }


}

