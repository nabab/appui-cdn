<div class="bbn-padded bbn-full-screen bbn-flex-height">
  <div class="bbn-flex-fill">
    <div class="bbn-flex-width bbn-h-20 bbn-vmiddle">
      <bbn-input placeholder="<?=_('Select file')?>"
                 v-model="element"
                 @keydown.enter="addLanguage"
                 class="bbn-w-100 bbn-h-20 bbn-padded"
      ></bbn-input>
      <bbn-button @click="addLanguage"
                  icon="fas fa-plus"
                  title="<?=_('Add file')?>"
                  v-if ="element.length"
                  class="bbn-flex-fill bbn-h-20"
      ></bbn-button>
    </div>
  </div>
  <div class="bbn-h-100">
    <bbn-tree :source="source.tree"
              :map="mapMenu"
              @select="selectElement"
    ></bbn-tree>
  </div>
</div>
