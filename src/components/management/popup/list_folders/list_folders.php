<bbn-form class="bbn-c"
          ref="form"
          :buttons="buttonsAction"
          :prefilled="true"
          @submit="submit"
>
  <div class="bbn-w-100 bbn-padded" bbn-xl>
    <bbn-dropdown style="width:300px"
                  ref="listFolders"
                  placeholder= "<?= _('Select version directory') ?>"
                  :source="folders"
                  :required="true"
                  v-model="folderId"
    ></bbn-dropdown>
    <bbn-button v-if="folderId.length"
                @click="deleteFolder"
                icon="nf nf-fa-trash"
                title="<?= _('Delete Folder') ?>"
    ></bbn-button>
  </div>
</bbn-form>
