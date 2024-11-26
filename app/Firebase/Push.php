<?php

namespace App\Firebase;

/**
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Push {

    // push message title
    private $image;
    // private $title;
    // private $message;
    private $type_notif;
    // push message payload
    private $data;
    // flag indicating whether to show the push
    // notification or not
    // this flag will be useful when perform some opertation
    // in background when push is recevied
    private $is_background;

    function __construct() {
        
    }

    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    }
    public function setPayload($data) {
        $this->data = $data;
    }
    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }
    // public function setTitle($title){
    //     $this->title = $title;
    // }
    // public function setMessage($message){
    //     $this->message = $message;
    // }
    public function setTypeNotif($type_notif){
        $this->type_notif = $type_notif;
    }
    public function setNotification($notification) {
        $this->notification = $notification;
    }

    public function getPush() {
        $res = array();
        // $res['notification']    = $this->notification;
        // $res['title']           = $this->title;
        // $res['message']         = $this->message;
        // $res['type']            = $this->type_notif;
        $res['payload']         = $this->data;
    
        return $res;
    }

}
