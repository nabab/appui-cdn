(()=>{
  return{
    data(){
      return {
        urlGit: "",
        btns:[
          {
            text: 'calcel',
            command: ()=>{
              bbn.vue.closest(this, "bbn-popup").close();
            },
            icon: 'fa fa-ban',
            title: 'cancel',
          },{
            text: 'Skip',
            command: ()=>{
             this.addManualyLib()
            },
            title: 'skip',
            icon: 'fa fa-angle-right'
        },{
            text: 'Import',
            command: ()=>{
             this.importGithub()
            },
            icon: "fa fa-angle-double-right",
            title: 'import',
          }
        ]
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
                bbn.vue.closest(this, 'bbn-tab').popup().open({
                  height: '80%',
                  width: '60%',
                  title: bbn._("Import library of github:"),
                  component:'appui-cdn-management-library_edit',
                  source: {row: d.data, addLibrary: true}
                });
              }
            }
          );
        }
      },
      addManualyLib(){
        bbn.vue.closest(this, 'bbn-tab').popup().open({
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
              addLibrary: true
            }
        });
      }
    }
  }
})();
