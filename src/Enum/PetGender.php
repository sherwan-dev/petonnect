<?php 
namespace App\Enum;

enum PetGender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case UNKNOWN = 'unknown';
}
