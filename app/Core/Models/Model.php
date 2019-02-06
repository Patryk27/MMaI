<?php

namespace App\Core\Models;

use App\Core\Exceptions\Exception;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel {

    /**
     * @return void
     * @throws Exception
     */
    public function saveOrThrow(): void {
        if ($this->save() === false) {
            throw new Exception('Failed to save model.');
        }
    }

}
