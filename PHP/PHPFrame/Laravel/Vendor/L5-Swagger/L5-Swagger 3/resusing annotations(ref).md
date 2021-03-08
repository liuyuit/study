# resusing annotations(ref)

## references

> https://zircote.github.io/swagger-php/Getting-started.html#reusing-annotations-ref

```
    /**
     * @OA\Schema(
     *     schema="product_id",
     *     type="integer",
     *     format="int64",
     *     description="The unique identifier of roduct in our catalog"
     * )
     * @OA\Schema(
     *     schema="product_name1",
     *     type="string",
     *     description="The name of product"
     * )
     * @OA\Schema(
     *     schema="product",
     *     type="object",
     *     @OA\Property(
     *          property="id",
     *          ref="#components/schemas/product_id"
     *     ),
     *     @OA\Property(
     *          property="name",
     *          ref="#components/schemas/product_name"
     *     )
     * )
     * @param IndexRequest $request
     * @return mixed
     */
```

result

```
"product_id": {
"description": "The unique identifier of roduct in our catalog",
"type": "integer",
"format": "int64"
},
"product_name1": {
"description": "The name of product",
"type": "string"
},
"product": {
"properties": {
"id": {
"$ref": "#components/schemas/product_id"
},
"name": {
"$ref": "#components/schemas/product_name"
}
},
"type": "object"
}
```

#### 对象复用

定义对象

```
* @OA\Schema (
 *     schema="ActiveLogBody",
 *     type="object",
 *     @OA\Property(
 *         property="gid",
 *         description="数据产生的游戏",
 *         ref="#/components/schemas/gid"
 *     ),
 *     @OA\Property(
 *         property="lid",
 *         description="数据产生的包",
 *         ref="#/components/schemas/lid"
 *     ),
 * )
```

对象可以直接使用

```
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
     *                  ref="#/components/schemas/ActiveLogBody"
     *              )
     *          )
     *     ),
     *     @OA\Response(
     *          response="200",
     *          description="responcse succesful"
     *     )
     * )
```

这样就会出现 ActiveLogBody 定义的所有字段

但如果想在这个对象的基础上再加字段，就使用 allOf

```
     *     @OA\RequestBody(
     *          request="active",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="rest",
     *                      ref="#/components/schemas/rest",
     *                  ),
     *                  allOf={
     *                      @OA\Schema(
     *                          ref="#/components/schemas/ActiveLogBody",
     *                      )
     *                  }
     *              )
     *          )
     *     ),
```

但如果想在这个对象的基础上扩展一个新对象

```
 * @OA\Schema (
 *     schema="ActiveLog",
 *     @OA\Property(
 *         property="admin",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AdminBody")
 *     ),
 *     @OA\Property(
 *         property="team",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/TeamBody")
 *     ),
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/ActiveLogBody")
 *     }
 * )
```

