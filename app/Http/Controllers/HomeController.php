<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Example data - you can replace with actual database queries
        $featuredPosts = [
            [
                'title' => 'Getting Started with Laravel',
                'excerpt' => 'Laravel is a powerful PHP framework with elegant syntax and amazing features.',
                'url' => '#'
            ],
            [
                'title' => 'Web Development Tips',
                'excerpt' => 'Discover top tricks to improve your web development workflow.',
                'url' => '#'
            ]
        ];
        
        return view('homepage', [
            'featuredPosts' => $featuredPosts
        ]);
    }
}