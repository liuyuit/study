## requestBody

> https://github.com/zircote/swagger-php/blob/master/Examples/example-object/example-object.php

```
/**
 * @OA\Post(
 *     path="/users",
 *     summary="Adds a new user",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="name",
 *                     type="string"
 *                 ),
 *                 example={"id": "a3fb6", "name": "Jessica Smith"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK"
 *     )
 * )
 */
 
   
openapi: 3.0.0
info:
  title: 'Example for response examples value'
  version: '1.0'
paths:
  /users:
    post:
      summary: 'Adds a new user'
      requestBody:
        content:
          application/json:
            schema:
              properties:
                id:
                  type: string
                name:
                  type: string
              type: object
              example:
                id: a3fb6
                name: 'Jessica Smith'
      responses:
        '200':
          description: OK
```

```
    /**
     * @OA\Post(
     *     path="/report/active",
     *     summary="report active",
     *     tags={"Api.Report"},
     *     @OA\RequestBody(
     *          request="active",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                   ref="#/components/schemas/ActiveLogBody"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="responcse succesful"
     *     )
     * )
     * @param IndexRequest $request
     * @return mixed
     */
     
     
     
     /**
 * Class Active
 *
 * @OA\Schema (
 *     schema="ActiveLogBody",
 *     type="object",
 *     @OA\Property(
 *         property="created_at",
 *         ref="#/components/schemas/created_at"
 *     ),
 *     @OA\Property(
 *         property="gid",
 *         description="数据产生的游戏",
 *         ref="#/components/schemas/gid"
 *     ),
 *     @OA\Property(
 *         property="equipment_idfv",
 *         description="我们自己制作的",
 *         ref="#/components/schemas/equipmentidfv"
 *     ),
 * )
 */
 
 
 {
    "/report/active": {
        "post": {
            "tags": [
                "Api.Report"
            ],
            "summary": "report active",
            "operationId": "App\\Http\\Controllers\\Api\\V1\\Report\\ActiveController::index",
            "requestBody": {
                "request": "active",
                "required": true,
                "content": {
                    "application/x-www-form-urlencoded": {
                        "schema": {
                            "$ref": "#/components/schemas/ActiveLogBody"
                        }
                    }
                }
            },
            "responses": {
                "200": {
                    "description": "responcse succesful"
                }
            }
        }
    }
}
     
```

