<?php declare(strict_types=1);

namespace Stefna\Http\Response;

enum Status: int
{
	// Informational 1xx
	case Continue = 100;
	case SwitchingProtocols = 101;
	case Processing = 102;
	case EarlyHints = 103;

	// Successful 2xx
	case Ok = 200;
	case Created = 201;
	case Accepted = 202;
	case NonAuthoritativeInformation = 203;
	case NoContent = 204;
	case ResetContent = 205;
	case PartialContent = 206;
	case MultiStatus = 207;
	case AlreadyReported = 208;
	case ImUsed = 226;

	// Redirection 3xx
	case MultipleChoices = 300;
	case MovedPermanently = 301;
	case Found = 302;
	case SeeOther = 303;
	case NotModified = 304;
	case UseProxy = 305;
	case TemporaryRedirect = 307;
	case PermanentRedirect = 308;

	// Client Errors 4xx
	case BadRequest = 400;
	case Unauthorized = 401;
	case PaymentRequired = 402;
	case Forbidden = 403;
	case NotFound = 404;
	case MethodNotAllowed = 405;
	case NotAcceptable = 406;
	case ProxyAuthenticationRequired = 407;
	case RequestTimeout = 408;
	case Conflict = 409;
	case Gone = 410;
	case LengthRequired = 411;
	case PreconditionFailed = 412;
	case PayloadTooLarge = 413;
	case UriTooLong = 414;
	case UnsupportedMediaType = 415;
	case RangeNotSatisfiable = 416;
	case ExpectationFailed = 417;
	case ImATeapot = 418;
	case MisdirectedRequest = 421;
	case UnprocessableEntity = 422;
	case Locked = 423;
	case FailedDependency = 424;
	case TooEarly = 425;
	case UpgradeRequired = 426;
	case PreconditionRequired = 428;
	case TooManyRequests = 429;
	case RequestHeaderFieldsTooLarge = 431;
	case UnavailableForLegalReasons = 451;

	// Server Errors 5xx
	case InternalServerError = 500;
	case NotImplemented = 501;
	case BadGateway = 502;
	case ServiceUnavailable = 503;
	case GatewayTimeout = 504;
	case VersionNotSupported = 505;
	case VariantAlsoNegotiates = 506;
	case InsufficientStorage = 507;
	case LoopDetected = 508;
	case NotExtended = 510;
	case NetworkAuthenticationRequired = 511;

	/** @var array<int, string> Map of standard HTTP status code/reason phrases */
	private const PHRASES = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-status',
		208 => 'Already Reported',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		306 => 'Switch Proxy',
		307 => 'Temporary Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Time-out',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Large',
		415 => 'Unsupported Media Type',
		416 => 'Requested range not satisfiable',
		417 => 'Expectation Failed',
		418 => 'I\'m a teapot',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Unordered Collection',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		451 => 'Unavailable For Legal Reasons',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Time-out',
		505 => 'HTTP Version not supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		511 => 'Network Authentication Required',
	];

	public function getPhrase(): string
	{
		return self::PHRASES[$this->value] ?? '';
	}
}
