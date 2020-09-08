(()=>{
  return{
    data(){
      return {
        urlGit: "",
        btns:[
          {
            text: bbn._('Cancel'),
            action: ()=>{
              bbn.vue.closest(this, "bbn-popup").close();
            },
            icon: 'nf nf-fa-ban',
            title: bbn._('cancel'),
          },{
            text: bbn._('Skip'),
            action: ()=>{
             this.addManualyLib()
            },
            title: bbn._('skip'),
            icon: 'nf nf-fa-angle_double_right',
            class: "bbn-primary",
          },{
            text: bbn._('Import'),
            action: ()=>{
             this.importGithub()
            },
            icon: "nf nf-fa-angle_right",
            title: bbn._('import'),
            class: "",
            disabled: true
          }
        ]
      }
    },
    computed:{
      management(){
        return this.closest("bbn-container").getComponent()
      }
    },
    methods:{
      importGithub(){
        if ( this.urlGit.length ){
          this.post(this.management.source.root + "github/info", {
              url: this.urlGit,
              only_info: false
            },
            (d) => {
              if ( d.data ){
                for (var prop in d.data){
                  if ( (prop !== 'name') && (prop !== 'latest') && (this[prop] !== undefined) ){
                    if ( prop === 'licence' ){
                      let lic = bbn.fn.getField(data.licences, 'licence', 'name', d.data[prop]);
                      if ( !lic ){
                        lic = bbn.fn.getField(data.licences, 'licence', 'licence', d.data[prop]);
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
                    title: bbn._("Import library from Github"),
                    component:'appui-cdn-management-library_edit',
                    source: {
                      row: d.data,
                      import: true
                    }
                  });
                }
                else{
                  bbn.vue.closest(this, 'bbn-container').popup().alert(bbn._("No release found"));
                }
              }
            }
          );
        }
        else{
          bbn.vue.closest(this, "bbn-popup").alert(bbn._("The repository's URL is empty"));
        }
      },
      addManualyLib(){
        let pop = bbn.vue.closest(this, 'bbn-container').popup();
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
          this.btns[idImport].class = "bbn-primary";
          this.btns[idImport].disabled = false;
          this.btns[idSkip].class = "";
        }
        else{
          this.btns[idImport].class = "";
          this.btns[idImport].disabled = true;
          this.btns[idSkip].class = "bbn-primary";

        }
      }
    }
  }
})();