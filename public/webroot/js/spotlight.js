/**
 * @property {HTMLInputElement} input
 * @property {SpotlightItem[]} items
 * @property {SpotlightItem[]} matchedItems
 * @property {SpotlightItem} activeItem
 * @property {HTMLULListElement} suggestions
 */
class Spotlight extends HTMLElement {

    constructor() {
        super()
        this.shortcutHandler = this.shortcutHandler.bind(this);
        this.hide = this.hide.bind(this);
        this.onInput = this.onInput.bind(this);
        this.inputControlKeyHandler = this.inputControlKeyHandler.bind(this); 
    }

    connectedCallback() {
        //Structure of HTML spotlight
        this.classList.add('spotlight')
        this.innerHTML = `
        <div class="spotlight-bar">
            <input type="text" name="q">
            <ul class="spotlight-suggestion" hidden>
            </ul>
        </div>`
        this.input = this.querySelector('input')
        this.input.addEventListener('blur', this.hide)

        //Suggestions list
        this.suggestions = this.querySelector('.spotlight-suggestion')
        this.items = Array.from(document.querySelectorAll(this.getAttribute('target'))).map(a => {
            const title = a.innerText.trim()
            if (title === '') return null;
            const item = new SpotlightItem(title, a.getAttribute('href'))
            this.suggestions.appendChild(item.element)
            return item
        }).filter(i => i !== null)

        //Listening of shortcuts
        window.addEventListener('keydown', this.shortcutHandler)
        this.input.addEventListener('input', this.onInput)
        this.input.addEventListener('keydown', this.inputControlKeyHandler)
    }

    shortcutHandler(e) {
        if (e.key === 'b' && e.ctrlKey === true) {
            e.preventDefault()
            this.classList.add('active')
            this.input.value = ''
            this.onInput()
            this.input.focus()
        }
    }

    onInput() {
        const search = this.input.value.trim()
        if (search === '') {
            this.items.forEach(item => item.hide())
            this.matchedItems = []
            this.suggestions.setAttribute('hidden', 'hidden')
            return
        }
        let regex = '^(.*)'
        for (const i in search) {
            regex += `(${search[i]})(.*)`
        }
        regex += '$'
        regex = new RegExp(regex, 'i')
        this.matchedItems = this.items.filter(item => item.match(regex)) 
        if (this.matchedItems.length > 0) {
            this.suggestions.removeAttribute('hidden')
            this.setActiveIndex(0)
        }else {
            this.suggestions.setAttribute('hidden', 'hidden')
        }
    }

    /**
     * @param {KeyboardEvent} e 
     */
    inputControlKeyHandler(e) {
        if (e.key === 'Escape') {
            this.input.blur()
        }else if(e.key === 'ArrowDown') {
            const index = this.matchedItems.findIndex(element => element === this.activeItem)
            console.log(index.valueOf() + 1)
            this.setActiveIndex(index + 1)
        }else if (e.key === 'ArrowUp') {
            const index = this.matchedItems.findIndex(element => element == this.activeItem)
            this.setActiveIndex(index - 1)
        }else if (e.key === 'Enter') {
            this.activeItem.redirect()
        }
    }

    setActiveIndex(n) {
        if (this.activeItem) this.activeItem.deactive()
        if (n >= this.matchedItems.length) n = 0
        if (n < 0) n = this.matchedItems.length - 1
        this.matchedItems[n].active()
        this.activeItem = this.matchedItems[n]
    }

    disconnectedCallback() {
        window.removeEventListener('keydown', this.shortcutHandler)
    }

    hide() {
        this.classList.remove('active')
    }

}

/**
 * @property {HTMLLiElement} element
 * @property {string} title
 * @property {string} href
 */
class SpotlightItem {

    /**
     * @param {string} title 
     * @param {string} href 
     */
    constructor(title, href) {
        const li = document.createElement('li')
        const a = document.createElement('a')
        a.setAttribute('href', href)
        a.innerText = title
        li.appendChild(a)
        this.element = li
        this.title = title
        this.href = href
        this.hide()
    }

    /**
     * @param {RegExp} regex 
     * @return {boolean} true match. false not.
     */
    match(regex) {
        const matches = this.title.match(regex)
        if (matches === null) {
            this.hide()
            return false
        }
        this.element.firstElementChild.innerHTML = matches.reduce((acc, match, index) => {
            if (index === 0) {
                return acc
            }
            return acc + (index % 2 === 0 ? `<span>${match}</span>` : match)
        }, '')
        this.show()
        return true
    }

    redirect() {
        window.location.href = this.href
    }

    hide() {
        this.element.setAttribute('hidden', 'hidden')
    }

    show() {
        this.element.removeAttribute('hidden')
    }

    active() {
        this.element.classList.add('active')
    }

    deactive() {
        this.element.classList.remove('active')
    }

}