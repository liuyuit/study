@echo off
E:
cd \Study
git pull
git add .
git commit -m "bat批处理上传"
git push origin master

E:
cd \Record
git pull
git add .
git commit -m "bat批处理上传"
git push origin master

E:
cd \xy_document
git pull
git add .
git commit -m "bat批处理上传"
git push origin master


E:
cd \Study\StudyCode\Algorithms
git pull
git add .
git commit -m "bat批处理上传"
git push origin master