<?php

require_once __DIR__ . '/../config/timezone.php';

class TimerService
{
    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../storage/json/timers.json';
    }

    private function load()
    {
        if (!file_exists($this->file)) {
            return [];
        }

        return json_decode(file_get_contents($this->file), true) ?? [];
    }

    private function save($data)
    {
        file_put_contents(
            $this->file,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }
    
    public function add($email, $note, $datetime)
    {
        $timers = $this->load();

        $timers[] = [
            'id'        => uniqid('timer_', true),
            'email'     => $email,
            'note'      => $note,
            'datetime'  => $datetime,
            'status'    => 'pending',
            'created_at'=> date('Y-m-d H:i:s')
        ];

        $this->save($timers);
    }
    
    public function dueTimers()
    {
        $now = time();
        $timers = $this->load();
        $due = [];

        foreach ($timers as &$t) {
            
            if (
                empty($t['id']) ||
                empty($t['datetime']) ||
                empty($t['status']) ||
                $t['status'] !== 'pending'
            ) {
                continue;
            }

            $ts = strtotime($t['datetime']);
            if ($ts === false || $ts > $now) {
                continue;
            }
            
            $t['status'] = 'processing';
            $due[] = $t;
        }

        
        
        $this->save($timers);

        return $due;
    }
    
    public function markSent($id)
    {
        $timers = $this->load();

        foreach ($timers as &$t) {
            if ($t['id'] === $id) {
                $t['status']  = 'sent';
                $t['sent_at'] = date('Y-m-d H:i:s');
            }
        }

        $this->save($timers);
    }
    
    public function markFailed($id)
    {
        $timers = $this->load();

        foreach ($timers as &$t) {
            if ($t['id'] === $id) {
                $t['status'] = 'failed';
            }
        }

        $this->save($timers);
    }
}