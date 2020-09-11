<?php
function convertChars($chars) {
    return htmlspecialchars($chars, ENT_QUOTES, 'UTF-8');
}