function text(address,back) {
    console.log(address);
    back(1);
}
text(2,(res)=>{
    console.log(res)
})