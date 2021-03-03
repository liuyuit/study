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

