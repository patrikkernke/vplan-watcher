<?php

namespace App\Http\Resources;

use App\Models\Disposition;
use Illuminate\Http\Resources\Json\JsonResource;

class AwaySpeakerResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $dispositions = collect([]);

        foreach ($this->dispositions as $disposition) {
            $dispo = Disposition::where('topic_id', $disposition)->first(['topic_id', 'topic']);
            $dispositions->push($dispo);
        }

        return [
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dispositions' => $dispositions,
            'email' => $this->email,
            'phone' => $this->phone,
            'may_give_speak_away' => (boolean) $this->may_give_speak_away,
            'is_dag' => (boolean) $this->is_dag,
            'notes' => $this->notes,
        ];
    }
}
