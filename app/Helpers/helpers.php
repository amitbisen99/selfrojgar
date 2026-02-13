<?php 

use App\Models\Notification;

/**
 * Write code on Method
 *
 * @return response()
 */
function notificationMsg($type, $message){
    \Session::put($type, $message);
}

function createNotification($title, $model, $model_id, $type)
{
    Notification::create([
                            'title' => $title,
                            'model' => $model,
                            'model_id' => $model_id,
                            'type' => $type,
                            'created_or_update_by' => auth()->user()->id,
                        ]);
}