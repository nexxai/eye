<?php

namespace Eyewitness\Eye\Queue\Handlers;

use Illuminate\Database\QueryException;

class DatabaseQueue extends BaseHandler
{
    /**
     * The queue table name.
     *
     * @var string
     */
    protected $table;

    /**
     * Create a new Database queue instance.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function __construct($connection, $queue)
    {
        $this->queue = $connection->getDatabase();

        $this->table = config("queue.connnections.{$queue->connection}.table");
    }

    /**
     * Return the number of pending jobs for the tube.
     *
     * @param  string  $tube
     * @return int
     */
    public function pendingJobsCount($tube)
    {
        try {
            $count = $this->queue->table($this->table)
                                 ->whereNull('reserved_at')
                                 ->where('queue', $tube)
                                 ->where('available_at', '<=', time())
                                 ->count();
        } catch (QueryException $e) {
            $count = 0;
        }

        return $count;
    }
}
