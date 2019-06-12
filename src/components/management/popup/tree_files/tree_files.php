<div class="bbn-overlay bbn-padded"
     style="display: grid; grid-template-rows: max-content auto"
>
  <div class="bbn-w-100 bbn-grid-width bbn-padded">
    <div class="bbn-w-80 bbn-middle">
      <bbn-input placeholder="<?=_('Select file')?>"
                 v-model="element"
                 @keydown.enter="addLanguage"
                 class="bbn-w-100 bbn-h-20"
      ></bbn-input>
    </div>
    <div class="bbn-flex-fill bbn-middle">
      <bbn-button @click="addLanguage"
                  icon="nf nf-fa-plus"
                  title="<?=_('Add file')?>"
                  v-if ="element.length"
                  class="bbn-h-20"
      ></bbn-button>
    </div>
  </div>
  <div class="bbn-padded">
    <bbn-tree :source="source.tree"
              :map="mapMenu"
              @select="selectElement"
    ></bbn-tree>
  </div>
</div>
