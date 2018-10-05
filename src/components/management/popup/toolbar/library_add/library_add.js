(()=>{
  return{
    data(){
      return {
        urlGit: "",
        btns:[
          {
            text: bbn._('Cancel'),
            command: ()=>{
              bbn.vue.closest(this, "bbn-popup").close();
            },
            icon: 'fas fa-ban',
            title: bbn._('cancel'),
          },{
            text: bbn._('Skip'),
            command: ()=>{
             this.addManualyLib()
            },
            title: bbn._('skip'),
            icon: 'fas fa-angle-double-right',
            class: "k-primary",
          },{
            text: bbn._('Import'),
            command: ()=>{
             this.importGithub()
            },
            icon: "fas fa-angle-right",
            title: bbn._('import'),
            class: "",
            disabled: true
          }
        ]
      }
    },
    computed:{
      management(){
        return this.closest("bbns-tab").getComponent()
      }
    },
    methods:{
      importGithub(){
        if ( this.urlGit.length ){
          bbn.fn.post(this.management.source.root + "github/info", {
              url: this.urlGit,
              only_info: false
            },
            (d) => {
              if ( d.data ){
                for (var prop in d.data){
                  if ( (prop !== 'name') && (prop !== 'latest') && (this[prop] !== undefined) ){
                    if ( prop === 'licence' ){
                      let lic = bbn.fn.get_field(data.licences, 'name', d.data[prop], 'licence');
                      if ( !lic ){
                        lic = bbn.fn.get_field(data.licences, 'licence', d.data[prop], 'licence');
                      }
                      if ( lic ){
                        d.data.licence = lic;
                      }
                    }
                  }
                  if ( prop === 'versions' ){
                    let vers = [];
                    for (let ele of d.data['versions'] ){
                      vers.push({
                        text: ele.text,
                        value: ele.id
                      });
                    }
                    d.data.versions = vers.length ? vers : [];
                  }
                }
                if ( d.data['versions'].length ){
                  this.getPopup().open({
                    height: '80%',
                    width: '70%',
                    title: bbn._("Import library of github:"),
                    component:'appui-cdn-management-library_edit',
                    source: {
                      row: d.data,
                      import: true
                    }
                  });
                }
                else{
                  bbn.vue.closest(this, 'bbns-tab').popup().alert(bbn._("Releases not found"));
                }
              }
            }
          );
        }
        else{
          bbn.vue.closest(this, "bbn-popup").alert(bbn._("Empty url github"));
        }
      },
      addManualyLib(){
        let pop = bbn.vue.closest(this, 'bbns-tab').popup();
        pop.open({
            height: '80%',
            width: '60%',
            title: bbn._("Add library"),
            component:'appui-cdn-management-library_edit',
            source: {
              row: {
                name: "",
                fname: "",
                description: "",
                author: "",
                licences: "",
                website: "",
                dowload_link: "",
                doc_link: " ",
                git: "",
                support_link: "",
                latest: "",
                version: ""
              },
              import: false
            }
        });
      }
    },
    watch:{
      //if a value is entered to the input then we enable the button otherwise no
      urlGit(url){
        let idImport = bbn.fn.search(this.btns, 'text', 'Import'),
          idSkip = bbn.fn.search(this.btns, 'text', 'Skip');
        if ( url.length ){
          this.btns[idImport].class = "k-primary";
          this.btns[idImport].disabled = false;
          this.btns[idSkip].class = "";
        }
        else{
          this.btns[idImport].class = "";
          this.btns[idImport].disabled = true;
          this.btns[idSkip].class = "k-primary";

        }
      }
    }
  }
})();