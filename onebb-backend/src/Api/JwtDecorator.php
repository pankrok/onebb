<?php

// api/src/OpenApi/JwtDecorator.php

declare(strict_types=1);

namespace App\Api;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model;
use ApiPlatform\Core\OpenApi\OpenApi;

final class JwtDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'acp_enabled' => [
                    'type' => 'boolean',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['EmailValidation'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'hash' => [
                    'type' => 'string',
                    'example' => 'c4ca4238a0b923820dcc509a6f75849b',
                ],
            ],
        ]);

        $schemas['EmailValidationCreditionals'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'success' => [
                    'type' => 'boolean',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['TokenCredentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'test',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'test',
                ],
            ],
        ]);

        $schemas['RefreshTokenCredentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [],
        ]);

        $schemas['InvalidCreditionals'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'code' => [
                    'type' => 'integer',
                    'readOnly' => true,
                ],
                'message' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);
        $schemas['TokenRefreshCredentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
            ],
        ]);

        $schemas['ConfigCreditionals'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'base_url' => [
                    'type' => 'string',
                    'example' => '/',
                ],
                'acp_url' => [
                    'type' => 'string',
                    'example' => 'admin',
                ],
                'mailer.dns' => [
                    'type' => 'string',
                    'example' => 'smtp://admin%40domain.com:password@domain.com:25',
                ],
                                'mail.validation' => [
                    'type' => 'boolean',
                    'example' => true,
                ],
                'mail.address' => [
                    'type' => 'string',
                    'example' => 'admin@domain.com',
                ],
                'mail.username' => [
                    'type' => 'string',
                    'example' => 'Admin',
                ],
            ],
        ]);

        $schemas['Config'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                 'base_url' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'acp_url' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],

                'version' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],

                'mailer.dns' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],

                'mail.validation' => [
                    'type' => 'boolean',
                    'readOnly' => true,
                ],

                'mail.address' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],

                'mail.username' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $pathConfiguration = new Model\PathItem(
            ref: 'OneBB configuration',
            get: new Model\Operation(
                operationId: 'getCredentialsItem',
                tags: ['Configuration'],
                responses: [
                    '200' => [
                        'description' => 'Success get configuration array',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Config',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'JWT Token not found',
                    ],
                    '403' => [
                        'description' => 'Failure configuration save',
                    ],
                ],
                summary: 'Recive OneBB configuration.',
                requestBody: new Model\RequestBody(
                    description: 'Set user validation to true',
                ),
            ),
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Configuration'],
                responses: [
                    '201' => [
                        'description' => 'Success configuration save',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Config',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'JWT Token not found',
                    ],
                    '403' => [
                        'description' => 'Failure configuration save',
                    ],
                ],
                summary: 'Recive OneBB configuration.',
                requestBody: new Model\RequestBody(
                    description: 'Set user validation to true',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/ConfigCreditionals',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $pathUserValidation = new Model\PathItem(
            ref: 'New user email validation',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['EmailValidation'],
                responses: [
                    '200' => [
                        'description' => 'Success validation response',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/EmailValidationCreditionals',
                                ],
                            ],
                        ],
                    ],

                    '403' => [
                        'description' => 'Failure response message, hash not found',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/EmailValidationCreditionals',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Validate user.',
                requestBody: new Model\RequestBody(
                    description: 'Set user validation to true',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/EmailValidation',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $pathItemLogin = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token'],
                responses: [
                    '200' => [
                        'description' => 'Success auth response',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'Failure response message',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/InvalidCreditionals',
                                ],
                            ],
                        ],
                    ],
                    '403' => [
                        'description' => 'Failure response message for banned users',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/InvalidCreditionals',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Retrieves JWT token and scope.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/TokenCredentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $pathItemRefresh = new Model\PathItem(
            ref: 'JWT Token Refresh',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Token Refresh'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                    '401' => [
                        'description' => 'Failure response message',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/InvalidCreditionals',
                                ],
                            ],
                        ],
                    ],
                    '403' => [
                        'description' => 'Failure response message for banned users',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/InvalidCreditionals',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh JWT.',
                requestBody: new Model\RequestBody(
                    description: 'Refresh JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/TokenRefreshCredentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/login', $pathItemLogin);
        $openApi->getPaths()->addPath('/api/refresh', $pathItemRefresh);
        $openApi->getPaths()->addPath('/api/validation', $pathUserValidation);
        $openApi->getPaths()->addPath('/api/configuration/get', $pathConfiguration);

        return $openApi;
    }
}
