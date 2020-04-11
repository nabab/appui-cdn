// Javascript Document
((data, ele) => {
  return () => {
    bbn.fn.log(data, ele);
    let fns = {};
    bbn.fn.iterate(bbn.fn, (fn, name) => {
      fns[name] = fn.toString();
    })
    bbn.fn.post('cdn/publishing/bbn-fn', {fns: fns}, d => {
      bbn.fn.log(d);
    })
  };
})();