<?php

namespace App\Jobs;

use App\Models\Opinion;
use App\Services\Summarizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SummarizeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $plainText, private int $opinionId)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(Summarizer $summarizer): void
    {
        $summary = $summarizer->summarize($this->plainText);

        $opinion = Opinion::where('id', $this->opinionId)->firstOrFail();
        $opinion->summary = $summary;
        $opinion->saveOrFail();
    }
}
