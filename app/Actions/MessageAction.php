<?php
namespace Delivery\Actions;


use Delivery\Helpers\SessionHandler;

class MessageAction extends Action{

    public function run()
    {
        if ( isset($this->params['message']) ) {
            $this->sendMessage();
        } else if ( isset($this->params['update_messages']) && isset($this->params['last_msg_date']) ) {
            $this->updateMessages();
        }
    }

    public function sendMessage() {
        $data = array(
            'message' => $this->params['message'],
            'user_hash_from' => SessionHandler::selectSession('user'),
            'date' => date('Y-m-d H:i:s')
        );

        if ( $this->database()->insert('Chat', $data) ) {
            echo 1;
        } else {
            echo 0;
        }

        die();
    }

    public function updateMessages() {
        $lastMsgDate = $this->params['last_msg_date'];

        $sql = "SELECT * FROM Users,Chat WHERE Users.user_hash = Chat.user_hash_from";

        if ( $lastMsgDate !== false ) {
            $sql .= " AND Chat.date > :date ";
        }

        $sql .= " ORDER BY date";

        $newMessages = $this->database()->fetchRowMany($sql, array(':date' => $lastMsgDate));

        echo json_encode($newMessages);
        die();

    }
}

