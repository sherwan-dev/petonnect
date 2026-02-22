<?php 
namespace App\Enum;

enum PostVisibility: string
{
    case PUBLIC = 'public';
    case FOLLOWERS = 'followers';
    case PRIVATE = 'private';
}
