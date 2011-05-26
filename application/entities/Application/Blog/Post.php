<?php
namespace Application\Blog;

/**
 * @Document(db="blog", collection="posts")
 */
class Post
{
	/**
     * @Id
	 */
	private $id;
	
	/**
	 * @Field(type="string")
	 */
	private $title;
	
	/**
     * @String
     */
    private $content;
    
    /**
     * @EmbedMany(targetDocument="Application\Blog\Comment")
     */
    private $comments = array();
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function setId($id)
    {
    	$this->id = (string) $id;
    	return $this;
    }
    
    public function getTitle()
    {
    	return $this->title;
    }
    
    public function setTitle($title)
    {
    	$this->title = (string) $title;
    	return $this;
    }
    
    public function getContent()
    {
    	return $this->content;
    }
    
    public function setContent($content)
    {
    	$this->content = (string) $content;
    	return $this;
    }
    
    public function getComments()
    {
    	return $this->comments;
    }
    
    public function addComment(\Application\Blog\Comment $comment)
    {
    	$this->comments[] = $comment;
    	return $this;
    }
}