<bbn-form :action="source.action"
          :data="infoForDelete"
          @success="success"
          @failure="failure"
          class="bbn-overlay bbn-padding"
          :prefilled="true"
>
  <div class="bbn-100 bbn-padding">
    <span class="bbn-b bbn-xxl" v-text="deleteElement"></span>
    <div class="bbn-vmiddle">
      <bbn-switch v-model="infoForDelete.removeFolder"
                  :value="true"
                  :novalue="false"
      ></bbn-switch>
      <span class="bbn-b bbn-padding" v-text="deleteFolderElement"></span>
    </div>
  </div>
</bbn-form>
