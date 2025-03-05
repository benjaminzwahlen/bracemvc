<?php
namespace benjaminzwahlen\bracemvc\common\enums;

enum Environment: string
{
    case DEV = "DEV";
    case TEST = "TEST";
    case UAT = "UAT";
    case PROD = "PROD";
}
