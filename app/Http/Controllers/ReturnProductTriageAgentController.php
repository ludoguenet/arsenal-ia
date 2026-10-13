<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ReturnProductTriageAgent;
use App\Models\ProductReturn;
use Illuminate\Http\Request;

class ReturnProductTriageAgentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, ProductReturn $return)
    {
        $res = (new ReturnProductTriageAgent)->prompt(
            "Product info: {$return->product->name}\n\n{$return->reason}\n\n{$return->customer_description}"
        );

        $return->update([
            'category' => $res['category'],
            'damage_level' => $res['damage_level'],
            'priority' => $res['priority'],
            'triage_summary' => $res['summary'],
            'recommended_action' => $res['recommended_action'],
            'requires_human_review' => $res['requires_human_review'],
            'triaged_at' => now(),
        ]);

        return $return->fresh();
    }
}
