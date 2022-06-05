<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    use HasFactory;

    private mixed $period_of_donation;
    private mixed $amount;
    /**
     * @var mixed|string
     */
    private mixed $phone;
    /**
     * @var mixed|string
     */
    private mixed $email;
    private mixed $name;
    private mixed $id;


}
