<?php

namespace App\Enums;

/**
 * HTTP Status Code Enum
 * 
 * Comprehensive enumeration of HTTP status codes as defined in RFC 7231, RFC 6585, and other RFCs.
 * Provides standardized status codes with their corresponding integer values and human-readable messages.
 * 
 * @see https://tools.ietf.org/html/rfc7231#section-6
 * @see https://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
 */
enum HttpStatusCode: int
{
    // 1xx Informational - Request received, continuing process
    /** The server has received the request headers and the client should proceed to send the request body */
    case CONTINUE = 100;
    /** The requester has asked the server to switch protocols and the server has agreed to do so */
    case SWITCHING_PROTOCOLS = 101;
    /** A WebDAV request may contain many sub-requests involving file operations */
    case PROCESSING = 102;
    /** Used to return some response headers before final HTTP message */
    case EARLY_HINTS = 103;

    // 2xx Success - The action was successfully received, understood, and accepted
    /** Standard response for successful HTTP requests */
    case OK = 200;
    /** The request has been fulfilled, resulting in the creation of a new resource */
    case CREATED = 201;
    /** The request has been accepted for processing, but the processing has not been completed */
    case ACCEPTED = 202;
    /** The server is a transforming proxy that received a 200 OK from its origin */
    case NON_AUTHORITATIVE_INFORMATION = 203;
    /** The server successfully processed the request and is not returning any content */
    case NO_CONTENT = 204;
    /** The server successfully processed the request, but is not returning any content */
    case RESET_CONTENT = 205;
    /** The server is delivering only part of the resource due to a range header sent by the client */
    case PARTIAL_CONTENT = 206;
    /** The message body that follows is by default an XML message and can contain a number of separate response codes */
    case MULTI_STATUS = 207;
    /** The members of a DAV binding have already been enumerated in a preceding part of the response */
    case ALREADY_REPORTED = 208;
    /** The server has fulfilled a request for the resource, and the response is a representation of the result of one or more instance-manipulations */
    case IM_USED = 226;

    // 3xx Redirection - Further action must be taken in order to complete the request
    /** Indicates multiple options for the resource from which the client may choose */
    case MULTIPLE_CHOICES = 300;
    /** This and all future requests should be directed to the given URI */
    case MOVED_PERMANENTLY = 301;
    /** Tells the client to look at (browse to) another URL */
    case FOUND = 302;
    /** The response to the request can be found under another URI using the GET method */
    case SEE_OTHER = 303;
    /** Indicates that the resource has not been modified since the version specified by the request headers */
    case NOT_MODIFIED = 304;
    /** The requested resource resides temporarily under a different URI */
    case USE_PROXY = 305;
    /** No longer used. Originally meant "Subsequent requests should use the specified proxy" */
    case SWITCH_PROXY = 306;
    /** In this case, the request should be repeated with another URI */
    case TEMPORARY_REDIRECT = 307;
    /** The request and all future requests should be repeated using another URI */
    case PERMANENT_REDIRECT = 308;

    // 4xx Client Error - The request contains bad syntax or cannot be fulfilled
    /** The server cannot or will not process the request due to an apparent client error */
    case BAD_REQUEST = 400;
    /** Similar to 403 Forbidden, but specifically for use when authentication is required and has failed or has not yet been provided */
    case UNAUTHORIZED = 401;
    /** Reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme */
    case PAYMENT_REQUIRED = 402;
    /** The request contained valid data and was understood by the server, but the server is refusing action */
    case FORBIDDEN = 403;
    /** The requested resource could not be found but may be available in the future */
    case NOT_FOUND = 404;
    /** A request method is not supported for the requested resource */
    case METHOD_NOT_ALLOWED = 405;
    /** The requested resource is capable of generating only content not acceptable according to the Accept headers sent in the request */
    case NOT_ACCEPTABLE = 406;
    /** The client must first authenticate itself with the proxy */
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    /** The server timed out waiting for the request */
    case REQUEST_TIMEOUT = 408;
    /** Indicates that the request could not be processed because of conflict in the current state of the resource */
    case CONFLICT = 409;
    /** Indicates that the resource requested is no longer available and will not be available again */
    case GONE = 410;
    /** The request did not specify the length of its content, which is required by the requested resource */
    case LENGTH_REQUIRED = 411;
    /** The server does not meet one of the preconditions that the requester put on the request header fields */
    case PRECONDITION_FAILED = 412;
    /** The request is larger than the server is willing or able to process */
    case PAYLOAD_TOO_LARGE = 413;
    /** The URI requested by the client is longer than the server is willing to interpret */
    case URI_TOO_LONG = 414;
    /** The request entity has a media type which the server or resource does not support */
    case UNSUPPORTED_MEDIA_TYPE = 415;
    /** The client has asked for a portion of the file (byte serving), but the server cannot supply that portion */
    case RANGE_NOT_SATISFIABLE = 416;
    /** The server cannot meet the requirements of the Expect request-header field */
    case EXPECTATION_FAILED = 417;
    /** This code was defined in 1998 as one of the traditional IETF April Fools' jokes */
    case IM_A_TEAPOT = 418;
    /** The request was directed at a server that is not able to produce a response */
    case MISDIRECTED_REQUEST = 421;
    /** The request was well-formed but was unable to be followed due to semantic errors */
    case UNPROCESSABLE_ENTITY = 422;
    /** The resource that is being accessed is locked */
    case LOCKED = 423;
    /** The request failed because it depended on another request and that request failed */
    case FAILED_DEPENDENCY = 424;
    /** Indicates that the server is unwilling to risk processing a request that might be replayed */
    case TOO_EARLY = 425;
    /** The client should switch to a different protocol such as TLS/1.3, given in the Upgrade header field */
    case UPGRADE_REQUIRED = 426;
    /** The origin server requires the request to be conditional */
    case PRECONDITION_REQUIRED = 428;
    /** The user has sent too many requests in a given amount of time ("rate limiting") */
    case TOO_MANY_REQUESTS = 429;
    /** The server is unwilling to process the request because either an individual header field, or all the header fields collectively, are too large */
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    /** A server operator has received a legal demand to deny access to a resource or to a set of resources */
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    // 5xx Server Error - The server failed to fulfill an apparently valid request
    /** A generic error message, given when an unexpected condition was encountered and no more specific message is suitable */
    case INTERNAL_SERVER_ERROR = 500;
    /** The server either does not recognize the request method, or it lacks the ability to fulfil the request */
    case NOT_IMPLEMENTED = 501;
    /** The server was acting as a gateway or proxy and received an invalid response from the upstream server */
    case BAD_GATEWAY = 502;
    /** The server cannot handle the request (because it is overloaded or down for maintenance) */
    case SERVICE_UNAVAILABLE = 503;
    /** The server was acting as a gateway or proxy and did not receive a timely response from the upstream server */
    case GATEWAY_TIMEOUT = 504;
    /** The server does not support the HTTP protocol version used in the request */
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    /** Transparent content negotiation for the request results in a circular reference */
    case VARIANT_ALSO_NEGOTIATES = 506;
    /** The server is unable to store the representation needed to complete the request */
    case INSUFFICIENT_STORAGE = 507;
    /** The server detected an infinite loop while processing the request */
    case LOOP_DETECTED = 508;
    /** Further extensions to the request are required for the server to fulfil it */
    case NOT_EXTENDED = 510;
    /** The client needs to authenticate to gain network access */
    case NETWORK_AUTHENTICATION_REQUIRED = 511;

    /**
     * Get the human-readable message for the HTTP status code.
     * 
     * @return string The descriptive message for this status code
     */
    public function getMessage(): string
    {
        return match ($this) {
            // 1xx Informational
            self::CONTINUE => 'Continue',
            self::SWITCHING_PROTOCOLS => 'Switching Protocols',
            self::PROCESSING => 'Processing',
            self::EARLY_HINTS => 'Early Hints',

            // 2xx Success
            self::OK => 'OK',
            self::CREATED => 'Created',
            self::ACCEPTED => 'Accepted',
            self::NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
            self::NO_CONTENT => 'No Content',
            self::RESET_CONTENT => 'Reset Content',
            self::PARTIAL_CONTENT => 'Partial Content',
            self::MULTI_STATUS => 'Multi-Status',
            self::ALREADY_REPORTED => 'Already Reported',
            self::IM_USED => 'IM Used',

            // 3xx Redirection
            self::MULTIPLE_CHOICES => 'Multiple Choices',
            self::MOVED_PERMANENTLY => 'Moved Permanently',
            self::FOUND => 'Found',
            self::SEE_OTHER => 'See Other',
            self::NOT_MODIFIED => 'Not Modified',
            self::USE_PROXY => 'Use Proxy',
            self::SWITCH_PROXY => 'Switch Proxy',
            self::TEMPORARY_REDIRECT => 'Temporary Redirect',
            self::PERMANENT_REDIRECT => 'Permanent Redirect',

            // 4xx Client Error
            self::BAD_REQUEST => 'Bad Request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::PAYMENT_REQUIRED => 'Payment Required',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not Found',
            self::METHOD_NOT_ALLOWED => 'Method Not Allowed',
            self::NOT_ACCEPTABLE => 'Not Acceptable',
            self::PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
            self::REQUEST_TIMEOUT => 'Request Timeout',
            self::CONFLICT => 'Conflict',
            self::GONE => 'Gone',
            self::LENGTH_REQUIRED => 'Length Required',
            self::PRECONDITION_FAILED => 'Precondition Failed',
            self::PAYLOAD_TOO_LARGE => 'Payload Too Large',
            self::URI_TOO_LONG => 'URI Too Long',
            self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
            self::RANGE_NOT_SATISFIABLE => 'Range Not Satisfiable',
            self::EXPECTATION_FAILED => 'Expectation Failed',
            self::IM_A_TEAPOT => 'I\'m a teapot',
            self::MISDIRECTED_REQUEST => 'Misdirected Request',
            self::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
            self::LOCKED => 'Locked',
            self::FAILED_DEPENDENCY => 'Failed Dependency',
            self::TOO_EARLY => 'Too Early',
            self::UPGRADE_REQUIRED => 'Upgrade Required',
            self::PRECONDITION_REQUIRED => 'Precondition Required',
            self::TOO_MANY_REQUESTS => 'Too Many Requests',
            self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
            self::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',

            // 5xx Server Error
            self::INTERNAL_SERVER_ERROR => 'Internal Server Error',
            self::NOT_IMPLEMENTED => 'Not Implemented',
            self::BAD_GATEWAY => 'Bad Gateway',
            self::SERVICE_UNAVAILABLE => 'Service Unavailable',
            self::GATEWAY_TIMEOUT => 'Gateway Timeout',
            self::HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
            self::VARIANT_ALSO_NEGOTIATES => 'Variant Also Negotiates',
            self::INSUFFICIENT_STORAGE => 'Insufficient Storage',
            self::LOOP_DETECTED => 'Loop Detected',
            self::NOT_EXTENDED => 'Not Extended',
            self::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',

            // Fallback for any unhandled status codes
            default => 'HTTP Status ' . $this->value,
        };
    }

    /**
     * Get the numeric HTTP status code.
     * 
     * @return int The HTTP status code as an integer
     */
    public function getCode(): int
    {
        return $this->value;
    }

    /**
     * Check if the status code indicates a successful response (2xx).
     * 
     * @return bool True if the status code is in the 2xx range
     */
    public function isSuccess(): bool
    {
        return $this->value >= 200 && $this->value < 300;
    }

    /**
     * Check if the status code indicates a client error (4xx).
     * 
     * @return bool True if the status code is in the 4xx range
     */
    public function isClientError(): bool
    {
        return $this->value >= 400 && $this->value < 500;
    }

    /**
     * Check if the status code indicates a server error (5xx).
     * 
     * @return bool True if the status code is in the 5xx range
     */
    public function isServerError(): bool
    {
        return $this->value >= 500 && $this->value < 600;
    }

    /**
     * Check if the status code indicates a redirection (3xx).
     * 
     * @return bool True if the status code is in the 3xx range
     */
    public function isRedirection(): bool
    {
        return $this->value >= 300 && $this->value < 400;
    }

    /**
     * Check if the status code indicates an informational response (1xx).
     * 
     * @return bool True if the status code is in the 1xx range
     */
    public function isInformational(): bool
    {
        return $this->value >= 100 && $this->value < 200;
    }

    /**
     * Get the category name of the HTTP status code.
     * 
     * @return string The category name (Informational, Success, Redirection, Client Error, Server Error, or Unknown)
     */
    public function getCategory(): string
    {
        return match (true) {
            $this->isInformational() => 'Informational',
            $this->isSuccess() => 'Success',
            $this->isRedirection() => 'Redirection',
            $this->isClientError() => 'Client Error',
            $this->isServerError() => 'Server Error',
            default => 'Unknown',
        };
    }
}
