<?php
if(!empty($view))
{
    echo view($view);
} else
{
    echo 'Oops tidak ada view yang dipilih!';
}