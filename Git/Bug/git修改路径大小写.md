# git修改路径大小写

## 起因

线上因为目录大小写的问题导致composer无法找到类

```
Class App\Http\Controllers\MgameOperate\VersionController does not exist
```

而本地的路径是

```
App\Http\Controllers\Mgameoperate\VersionController
```

## 修改路径大小写

直接修改目录名git无法识别到，git对大小写不敏感

```
$ git status
On branch master
Your branch is up to date with 'origin/master'.

nothing to commit, working tree clean
```

于是使用git命令修改

```
$ git mv Mgameoperate/  MgameOperate/
Rename from 'app/Http/Controllers/Mgameoperate' to 'app/Http/Controllers/MgameOperate/Mgameoperate' failed. Should I try again? (y/n) y
Rename from 'app/Http/Controllers/Mgameoperate' to 'app/Http/Controllers/MgameOperate/Mgameoperate' failed. Should I try again? (y/n) y
```

只能迂回一下使用两条命令了

```
$ git mv Mgameoperate/  MgameOperate_test/

$ git mv MgameOperate_test/ MgameOperate/
```

然后在用这条命令清空一下composer对类的缓存

```
composer dump-audoload
```

