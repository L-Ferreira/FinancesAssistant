<?php

namespace App\Policies;

use App\Document;
use App\Movement;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function delete(Movement $movement, Document $document) {
        return $movement->document_id == $document->id;
    }
}
