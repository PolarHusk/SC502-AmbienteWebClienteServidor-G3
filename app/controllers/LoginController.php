<?php

class LoginController extends Controller
{
    public function index()
    {
        $this->view('auth/login');
    }
}