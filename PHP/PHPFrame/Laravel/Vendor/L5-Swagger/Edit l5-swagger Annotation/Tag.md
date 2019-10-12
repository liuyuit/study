# Tag

## Reference Linking

> https://fishead.gitbooks.io/openapi-specification-zhcn-translation/content/versions/3.0.0.zhCN.html#tagObject

## Define

```
/**
 * @SWG\OpenApi(
 *     @OA\Tag(
 *          name="security",
 *          description="security description",
 *     ),
 * ),
 * @SWG\OpenApi(
 *     @OA\Tag(
 *          name="auth",
 *          description="auth description",
 *     ),
 * ),
 */
```

## Use

```
 * @OA\Post(
 *     path="security_overwrite",
 *     tags={"security","auth"},
 *     security={},
 *     @OA\Response(
 *          response="200",
 *          description="success",
 *     ),
 * )
```

