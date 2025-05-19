s=process.argv.slice(2),r="";
if(s.length)for(i=0;i<s[0].length;i++)
    for(j=i+1;j<=s[0].length;j++){t=s[0].slice(i,j)
if(t.length>r.length&&s.every(x=>x.includes(t)))r=t}
console.log(r)
