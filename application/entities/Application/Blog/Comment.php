<?php
namespace Application\Blog;

/**
 * @EmbeddedDocument
 */
class Comment
{
    /**
     * @Id
     */
    private $id;
    
    /**
     * @String
     */
    private $email;
    
    /**
     * @String
     */
    private $comment;
       
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = (string) $id;
        return $this;
    }
    
    public function getEmail()
    {
    	return $this->email;
    }
    
    public function setEmail($email)
    {
    	$this->email = (string) $email;
    	return $this;
    }
    
    public function getComment()
    {
    	return $this->comment;
    }
    
    public function setComment($comment)
    {
    	$this->comment = (string) $comment;
    	return $this;
    }
}