<div class="bbn-full-screen">
  <div class="bbn-flex-height bbn-padded">
    <div>
      <bbn-input class="bbn-w-100"
                 placeholder="select file"
                 v-model="element"
                 @keydown.enter="addLanguage"
      ></bbn-input>
    </div>
    <div class="bbn-padded bbn-flex-fill">
      <bbn-tree :source="source.tree"
                :map="mapMenu"
                class="bbn-full-screen"
                @select="selectElement"
      ></bbn-tree>
    </div>
  </div>
</div>
