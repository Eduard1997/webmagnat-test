<?php

namespace App\Http\Controllers;

use App\PollOptions;
use App\PollResults;
use Illuminate\Http\Request;

class PollController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getPollOptions() {
        $pollOptions = PollOptions::get();
        return response()->json(['poll_options' => $pollOptions]);
    }

    public function saveAnswer() {
        $data = $this->request->all();
        $answer = $data['answer'];
        $answerId = PollOptions::where('name', $answer)->value('id');
        $inserted = PollResults::insert([
            'poll_option_id' => $answerId
        ]);

        $totalAnswers = PollResults::count();

        $goodAnswerId = PollOptions::where('name', 'Good')->value('id');
        $fairAnswerId = PollOptions::where('name', 'Fair')->value('id');
        $neutralAnswerId = PollOptions::where('name', 'Neutral')->value('id');
        $badAnswerId = PollOptions::where('name', 'Bad')->value('id');

        $goodAnswers = PollResults::where('poll_option_id', $goodAnswerId)->count();
        $fairAnswers = PollResults::where('poll_option_id', $fairAnswerId)->count();
        $neutralAnswers = PollResults::where('poll_option_id', $neutralAnswerId)->count();
        $badAnswers = PollResults::where('poll_option_id', $badAnswerId)->count();

        $goodAnswerPercent = $goodAnswers > 0 ? $this->computePercent($goodAnswers, $totalAnswers) : 0;
        $fairAnswersPercent = $fairAnswers > 0 ? $this->computePercent($fairAnswers, $totalAnswers) : 0;
        $neutralAnswersPercent = $neutralAnswers > 0 ? $this->computePercent($neutralAnswers, $totalAnswers) : 0;
        $badAnswersPercent = $badAnswers > 0 ? $this->computePercent($badAnswers, $totalAnswers) : 0;

        $percentData = [
            'goodAnswerPercent' => $goodAnswerPercent,
            'fairAnswersPercent' => $fairAnswersPercent,
            'neutralAnswersPercent' => $neutralAnswersPercent,
            'badAnswersPercent' => $badAnswersPercent,
            'goodAnswerCount' => $goodAnswers,
            'badAnswerCount' => $badAnswers,
            'fairAnswerCount' => $fairAnswers,
            'neutralAnswerCount' => $neutralAnswers,
        ];

        if($inserted) {
            return response()->json(['success' => 'Vote inserted!', 'percents' => $percentData]);
        } else {
            return response()->json(['error' => 'Vote not inserted!']);
        }
    }

    public function computePercent($value, $total) {
        $percent = ($value * 100) / $total;
        return number_format($percent, 2, '.', '');
    }
}
