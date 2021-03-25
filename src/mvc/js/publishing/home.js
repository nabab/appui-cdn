// Javascript Document
(() => {
  return {
    data(){
      let fns = {};
      bbn.fn.iterate(bbn.fn, (fn, name) => {
        fns[name] = fn.toString();
      });
      return {
        fns: fns,
        fname: Object.keys(fns)
      }
    },
    methods: {
      genBBNJs(){
        bbn.fn.post('cdn/publishing/gen-fn', {fns: this.fns}, d => {
          bbn.fn.log(d);
        })
      },
      findBBNJs(){
        bbn.fn.post('cdn/publishing/find-fn', {fns: this.fname}, d => {
          bbn.fn.log(d);
        })
      },
      genPHP(){
        bbn.fn.post('cdn/publishing/gen-php', d => {
          bbn.fn.log(d);
        })
      },
      genVue(singleFiles){
        bbn.fn.post('cdn/publishing/components', {single: typeof singleFiles === 'boolean' ? singleFiles : false}, d => {
          bbn.fn.log(d);
        })
      }
    }
  }
})();