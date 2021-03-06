<?php

namespace Eyewitness\Eye\Queue\Handlers;

use Aws\Sqs\Exception\SqsException;

class SqsQueue extends BaseHandler
{
    /**
     * Create a new Sqs queue instance.
     *
     * @param  \Illuminate\Contracts\Queue\Queue  $connection
     * @param  \Eyewitness\Eye\Repo\Queue  $queue
     * @return void
     */
    public function __construct($connection, $queue)
    {
        $this->queue = $connection;
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
            $result = $this->queue->getSqs()->getQueueAttributes([
                            'QueueUrl' => $this->queue->getQueue($tube),
                            'AttributeNames' => ['ApproximateNumberOfMessages']
                            ]);

            $count = $result['Attributes']['ApproximateNumberOfMessages'];
        } catch (SqsException $e) {
            $count = 0;
        }

        return $count;
    }
}
