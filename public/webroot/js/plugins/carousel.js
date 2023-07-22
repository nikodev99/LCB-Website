class Carousel {

    constructor (element, options = {}) {
      this.element = element
      this.options = Object.assign({}, {
        slidesToScroll: 1,
        slidesVisible: 1,
        buttons: true,
        loop: false,
        infinite: false,
        automatical: false
      }, options)
      if (this.options.loop && this.options.infinite) {
        console.error('Le carousel ne être à la fois en loop et en infini')
      }
      let children = [].slice.call(element.children)  
      this.isMobile = false                       
      this.currentItem = 0
      this.root = this.createDivWithClass('__carousel')
      this.container = this.createDivWithClass('__carousel-container')
      this.root.appendChild(this.container)
      this.element.appendChild(this.root)
      this.moveCallBacks = []
      this.offset = 0
      this.items = children.map((child) => {
        let item = this.createDivWithClass('__carousel-item')
        item.appendChild(child)
        return item
      })
      if (this.options.infinite) {
        this.offset = this.slidesVisible + this.slidesToScroll
        if (this.offset > children.length) {
          console.error("trop peu d'element")
        }
        this.items = [
          ...this.items.slice(this.items.length - this.offset).map(item => item.cloneNode(true)),
          ...this.items,
          ...this.items.slice(0, this.offset).map(item => item.cloneNode(true))
        ]
        this.gotto(this.offset, false)
        this.container.addEventListener('transitionend', this.resetInfinite.bind(this))
      }
      if (this.options.infinite && this.options.automatical) {
        window.setInterval(() => {
          this.container.addEventListener('transitionend', this.next())
        }, 5000)
      }
      this.items.forEach(item => this.container.appendChild(item))
      this.setStyle()
      this.navigation()
      this.moveCallBacks.forEach(cb => cb(this.currentItem))
      this.onWindowResize()
      window.addEventListener('resize', this.onWindowResize.bind(this))
    }
  
    /**
    * Permet de faire défiler le carousel en cliquant sur les boutons de navigations
    */
    navigation () {
      let nextButton = this.createDivWithClass('__carousel-next')
      let prevButton = this.createDivWithClass('__carousel-prev')
      this.root.appendChild(nextButton)
      this.root.appendChild(prevButton)
      if (!this.options.buttons) {
        nextButton.style.display = 'none'
        prevButton.style.display = 'none'
      }else {
        nextButton.addEventListener('click', this.next.bind(this))
        prevButton.addEventListener('click', this.prev.bind(this))
        this.onMove(index => {
        if (index === 0) {            
          prevButton.style.display = 'none'
        }else {
          prevButton.style.display = 'block'
        }
        if (this.items[this.currentItem + this.slidesVisible] === undefined) {      
          nextButton.style.display = 'none'
        }else {
          nextButton.style.display = 'block'
        }
      })
      }
      if (this.options.loop) {   
        return
      }
    }
  
    /**
    * Permet d'aller en avant. 
    */
    next() {      
      this.gotto(this.currentItem + this.slidesToScroll)
    }
  
    /**
    * Permet d'aller en arrière. 
    */
    prev() {     
      this.gotto(this.currentItem - this.slidesToScroll)
    }
  
    /**
    * Deplace le carousel vers l'element ciblé.
    * @param {number} {index}, l'index de l'element ciblé
    * @param {boolean} {animation = true},
    */
    gotto(index, animation = true) {
      if (index < 0) {
        if (this.options.loop) {
          index = this.items.length - this.slidesVisible
        }else {
          return
        }      
      }else if (index >= this.items.length || (index > this.currentItem && 
        this.items[this.currentItem + this.slidesVisible] === undefined)) {      
         if (this.options.loop) {
          index = 0
        }else {
          return
        }   
      }
      let x = index * -100 / this.items.length + '%'
      let y = 0
      let z = 0
      if (!animation) {
        this.container.style.transition = 'none'
      }
      this.container.style.transform =  'translate3d('+ x + ',' + y + ',' + z +')'
      this.container.style.transition =  'all .3s'
      this.container.offsetHeight
      if (!animation) {
        this.container.style.transition = null
      }
      this.currentItem = index
      this.moveCallBacks.forEach(cb => cb(index))
    }
  
    /**
    * Cree le slide infini
    */
    resetInfinite() {
      let index = 0;
      if (this.currentItem <= this.slidesToScroll) {
        index = this.currentItem + (this.items.length - 2 * this.offset)
        this.gotto(index, false)
      }else if (this.currentItem >= this.items.length - this.offset) {
        index = this.currentItem - (this.items.length - 2 * this.offset)
        this.gotto(index, false)
      }
    }
  
    /**
    * Ajoute un callBack à {moveCallBacks} chaque fois qu'il y a un mouvement
    */
    onMove(callBack) {
      this.moveCallBacks.push(callBack)
    }
  
    /**
    * Gere la responsive du carousel
    */
    onWindowResize() {
      let mobile = window.outerWidth < 800
      if (mobile !== this.isMobile) {
        this.isMobile = mobile
        this.setStyle()
        this.moveCallBacks.forEach(cb => cb(this.currentItem))
      }
    }
  
    /**
    * Donne la taille dynamiquement aux elements du carousel
    */
    setStyle() {       
      let ratio = this.items.length / this.slidesVisible
      this.container.style.width = (ratio * 100) + '%'
      this.items.forEach(item => item.style.width = ((100 / this.slidesVisible) / ratio) + '%')
    }
  
    /**
    * Permet de crée un {HTMLElement}
    * @param string {className}: le nom de la classe attribuée à l'element
    */
    createDivWithClass (className) {
      let div = document.createElement('div')
      div.setAttribute('class', className) 
      return div
    }
  
    get slidesToScroll () {
      return this.isMobile ? 1 : this.options.slidesToScroll
    }
  
    get slidesVisible () {
      return this.isMobile ? 1 : this.options.slidesVisible
    }
  
  }