<?php

namespace App\Enums;

enum ResponseStatusEnums: string
{
    //? Successful responses
    case LOGIN_SUCCESS  = 'User Login Successfully';
    case SUCCESS = 'Successfully Fetched';
    case CREATED = 'Item Created Successfully';
    case ACCEPTED = 'Item Accepted Successfully';

    case BAD_REQUEST = 'Invalid request parameters';
    case UNAUTHORIZED = 'Unauthorized Error!';
    case FORBIDDEN = 'Forbidden';
    case NOT_FOUND = 'No Item Found!';
    case NOT_ACCEPTABLE = 'Not Acceptable';
    case VALIDATION_FAILED = 'Validation Failed!';

    case SERVER_ERROR = 'Internal Server Error!';
    case SERVICE_UNAVAILABLE = 'Service Unavailable!';

        // for custom message
    case UPDATED = 'Item Updated Successfully';
    case DELETED = 'Item Deleted Successfully';
    case CONFIRMED = 'Item Confirmed Successfully';
    case REJECTED  = 'Item Rejected Successfully';
    case APPLIED   = 'Item Applied Successfully';
    case SELECTED  = 'Item Selected Successfully';
    case RESTORED = 'Item Restored Successfully';
}
