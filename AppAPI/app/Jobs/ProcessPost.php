<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Orhanerday\OpenAi\OpenAi;
use App\Models\Post;

class ProcessPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contents = [];
        foreach ($this->data as $k => $value) {
            // $value = substr($value,0,stripos($value,'</p>'));
            $value = trim(preg_replace('/\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($value))));
            if (!empty($value)) {
                $open_ai_key = env('OPENAI_API_KEY', false);
                $open_ai = new OpenAi($open_ai_key);

                $keyword = 'Viết một bài hoàn chỉnh theo từ khóa "' . trim($value) . '"';
                $response = $open_ai->chat([
                    'model' => 'gpt-3.5-turbo',
                    'messages' =>      [
                        [
                            "role" => "system",
                            "content" => $keyword,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => 4000,
                    'frequency_penalty' => 0,
                    'presence_penalty' => 0,
                ]);
                $result = json_decode($response, TRUE);
                $contents[] = trim(preg_replace('/\s+/', ' ', '<p>' . ucwords($value) . '</p><p>' . $result['choices'][0]['message']['content'])) . '</p>';
            }
        }
        $post = new Post();
        $post->title = 'demo';
        $post->content = implode(' ',$contents);
        $post->save();
    }
}
