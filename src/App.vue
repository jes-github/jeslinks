<template>
	<Content id="content" class="app-Werkstaat">
		<AppNavigation>
			<div>
				<div v-if="isAdmin"
					class="adminvakje">
					<h2 class="titelAN">Enkel voor admin</h2>
					<ul>
						<h2 class="titelAN">Links geven aan...</h2>
						<ActionInput
							@change="perPersoon($event.target.value)"
							icon="icon-search" />
						<div style="width: 100%; display: block; margin-bottom: 10px;">
						</div>
						<AppNavigationItem v-for="pnote in AanvraagPAr"
							:key="pnote.id"
							:title="pnote.voornaam + ' ' + pnote.achternaam"
							@click="toonWLinksVan(pnote.uid, pnote.emailinvoer)">
						</AppNavigationItem>
						<AppNavigationNew v-if="isAdmin"
							text="Afgeschermde werklink aanmaken"
							title="Afgeschermde werklink aanmaken"
							button-class="icon-add"
							@click="newWNote">
						</AppNavigationNew>
					</ul>
				</div>
				<h2 class="titelAN">
					JES links
				</h2>
				<ul>
					<AppNavigationItem
						title="Afgeschermde werklinks"
						class="werklinknavM"
						@click="toonWLinks()">
					</AppNavigationItem>
					<AppNavigationItem v-for="note in AanvraagAr"
						:key="note.id"
						:title="note.categorie"
						:class="{active: currentNoteId === note.id}"
						@click="toonLinks(note.categorie, JESL, 'jeslinks')">
					</AppNavigationItem>
				</ul>
				<AppNavigationNew v-if="(isAdmin && AanvraagAr.length === 0)"
					:disabled="false"
					text="Een JES-link toevoegen"
					button-id="new-Aanvragen-button"
					button-class="icon-add"
					@click="newNote" />
				<h2 class="titelAN">
					Jouw favorieten
				</h2>
				<ul>
					<AppNavigationItem v-for="notep in AanvraagPrivAr"
						:key="notep.id"
						:title="notep.categorie"
						:class="{active: currentPNoteId === notep.id}"
						@click="toonLinks(notep.categorie, notep.wie, 'persoonlijk')">
					</AppNavigationItem>
				</ul>
				<AppNavigationNew v-if="AanvraagPrivAr.length === 0"
					text="Een link toevoegen"
					:disabled="false"
					button-id="new-Aanvragen-button"
					button-class="icon-add"
					@click="newPNote" />
			</div>
			<template #footer>
				<AppNavigationSettings title="meer">
					<ul>
						<AppNavigationItem
							title="eerst opvragen..."
							@click="toekenbaar">
							<template slot="actions">
								<ActionButton
									icon="icon-info"
									@click="toonConfigWLinks">
									dan tonen...
								</ActionButton>
							</template>
						</AppNavigationItem>
					</ul>
				</AppNavigationSettings>
			</template>
		</AppNavigation>
		<AppContent>
			<h1>
				JES links en rechts...
			</h1>
			<div v-if="(currentPNote && lengteAr >= 1 && priveAdmin && Plinks)"
				class="vraagcontainer">
				<div class="in-takenvak">
					<h3>Een link aanmaken:</h3>
					<input ref="wie"
						v-model.trim="currentPNote.wie"
						type="hidden"
						placeholder="userId"
						:disabled="updating">
					<input ref="email"
						v-model="currentPNote.email"
						type="hidden"
						placeholder="email"
						:disabled="updating">
					<input ref="url"
						v-model="currentPNote.url"
						type="text"
						placeholder="De link/url"
						:disabled="updating">
					<input ref="naam"
						v-model="currentPNote.naam"
						type="text"
						placeholder="Een naam voor de link"
						:disabled="updating">
					<input ref="pasw"
						v-model="currentPNote.pasw"
						type="hidden"
						placeholder="een eventueel paswoord"
						:disabled="updating">
					<input ref="categorie"
						v-model="currentPNote.categorie"
						type="text"
						placeholder="Een categorie voor de link"
						:disabled="updating">
					<input ref="groep"
						v-model="currentPNote.groep"
						type="hidden"
						placeholder="Welke JES groep krijgt automatisch deze link"
						:disabled="updating">
					<input type="button"
						class="primary"
						value="Opslaan"
						:disabled="updating || !savePPossible"
						@click="savePNote">
					<input type="button"
						class="secondary"
						value="Annuleren"
						:disabled="updating || !savePPossible"
						@click="cancelPNote">
				</div>
			</div>
			<div v-if="(currentNote && lengteAr >= 1 && jesAdmin && Jlinks)"
				class="vraagcontainer">
				<div class="in-takenvak">
					<h3>Een JES-link aanmaken:</h3>
					<input v-model.trim="currentNote.wie"
						type="hidden"
						placeholder="userId"
						:disabled="updating">
					<input v-model="currentNote.email"
						type="hidden"
						placeholder="email"
						:disabled="updating">
					<input ref="urlJ"
						v-model="currentNote.url"
						type="text"
						placeholder="De link/url"
						:disabled="updating">
					<input ref="naam"
						v-model="currentNote.naam"
						type="text"
						placeholder="Een naam voor de link"
						:disabled="updating">
					<input v-model="currentNote.pasw"
						type="hidden"
						placeholder="een eventueel paswoord"
						:disabled="updating">
					<input v-model="currentNote.categorie"
						type="text"
						placeholder="Een categorie voor de link"
						:disabled="updating">
					<input v-model="currentNote.groep"
						type="hidden"
						placeholder="Welke JES groep krijgt automatisch deze link"
						value="iedereen"
						:disabled="updating">
					<input type="button"
						class="primary"
						value="Opslaan"
						:disabled="updating || !savePossible"
						@click="saveNote">
					<input type="button"
						class="secondary"
						value="Annuleren"
						:disabled="updating || !savePPossible"
						@click="cancelJNote">
				</div>
			</div>
			<div v-if="(currentWNote && lengteAr >= 1 && werkAdmin && TKAlinks)"
				class="vraagcontainer">
				<div class="in-takenvak">
					<h3>Een afgeschermde link aanmaken:</h3>
					<input v-model.trim="currentWNote.wie"
						type="hidden"
						placeholder="userId"
						:disabled="updating">
					<input v-model="currentWNote.email"
						type="hidden"
						placeholder="email"
						:disabled="updating">
					<input ref="urlW"
						v-model="currentWNote.url"
						type="text"
						placeholder="De link/url"
						:disabled="updating">
					<input ref="naam"
						v-model="currentWNote.naam"
						type="text"
						placeholder="Een naam voor de link"
						:disabled="updating">
					<input v-model="currentWNote.pasw"
						type="password"
						placeholder="een eventueel paswoord"
						:disabled="updating">
					<span>
						Paswoord ingeven of standaard paswoord gebruiken
					</span>
					<br>
					<input v-model="currentWNote.categorie"
						type="hidden"
						:disabled="updating">
					<input v-model="currentWNote.groep"
						type="hidden"
						:disabled="updating">
					<input type="button"
						class="primary"
						value="Opslaan"
						:disabled="updating || !saveWPossible"
						@click="saveWNote">
					<input type="button"
						class="secondary"
						value="Annuleren"
						:disabled="updating || !saveWPossible"
						@click="cancelWNote">
				</div>
			</div>
			<div v-if="Jlinks"
				class="vraagcontainer">
				<h3 class="h3inline">JES links: {{ gekozenJCategorie }}</h3>
				<input v-if="isAdmin"
					:disabled="false"
					type="button"
					text="Een JES-link toevoegen"
					button-id="new-Aanvragen-button"
					class="icon-add toevoegknopje"
					@click="newNote" />
				<div v-for="(klik, k) in KlikbaarAr"
					:key="k"
					class="linklijn">
					<a :href="klik.url" target="_blank">{{ klik.naam }}</a>
					<input type="button"
						@click="klembord(klik.url, $event)"
						class="icon-clippy copknopje"
						title="kopieer link naar klembord">
					<input v-if="isAdmin"
						type="button"
						class="icon-delete delknopje"
						@click="linkWeg(klik.id, 'jeslinks')">
				</div>
			</div>
			<div v-if="TKlinks"
				class="vraagcontainer">
				<h3 class="h3inline">Afgeschermde werk links toekennen</h3>
				<div v-for="(klik, k) in KlikbaarAr"
					:key="k"
					class="linklijn">
					<div class="naamlink">
						{{ klik.naam }} {{ klik.id }}
					</div>
					{{ nakijken(klik.naam, k) }}
					<input v-if="klik.opslagknop"
						class="success toekenknop"
						type="button"
						style="display: inline"
						value="Werklink schrappen"
						@click="linkWeg(klik.teschrappen, 'TK')">
					<input v-if="!klik.opslagknop"
						class="primary toekenknop"
						style="display: inline"
						type="button"
						value="Werklink toekennen"
						@click="LinkBijVoor(k)">
				</div>
			</div>
			<div v-if="Wlinks"
				class="vraagcontainer">
				<h3 class="h3inline">Jouw afgeschermde werk links</h3>
				<div v-for="(klik, k) in KlikbaarAr"
					:key="k"
					class="linklijn">
					<a @click="naarWerklink(klik.url, klik.pasw)"
						class="werklinknav">
						{{ klik.naam }}
					</a>
				</div>
				<form v-if="formAan"
					ref="wlform"
					:action="formLink"
					method="POST"
					target="_blank">
					<input v-model="formLink"
						type="hidden"
						name="delink" />
					<input v-model="formPW"
						type="hidden"
						name="paswoord" />
					<input v-model="formEm"
						type="hidden"
						name="email" />
					<input type="hidden"
						value="TOOB"
						name="codepas" />
					<input type="submit"
						value="test" />
				</form>
			</div>
			<div v-if="Plinks"
				class="vraagcontainer">
				<h3 class="h3inline">Jouw favorieten: {{ gekozenPCategorie }}</h3>
				<input :disabled="false"
					type="button"
					text="Een link toevoegen"
					button-id="new-Aanvragen-button"
					class="icon-add toevoegknopje"
					@click="newPNote" />
				<div v-for="(klik, k) in KlikbaarAr"
					:key="k"
					class="linklijn">
					<a :href="klik.url" target="_blank">{{ klik.naam }}</a>
					<input type="button"
						class="icon-clippy copknopje"
						title="kopieer link naar klembord"
						@click="klembord(klik.url, $event)">
					<input type="button"
						title="Delete"
						class="icon-delete delknopje"
						@click="linkWeg(klik.id, 'persoonlijk')">
				</div>
			</div>
		</AppContent>
		<AppSidebar
			v-show="show"
			title="Reserveerbaar:"
			@close="close">
			<input type="button"
				class="primary"
				style="margin-left:10px"
				value="Toon enkel wat nog vrij is"
				@click="nogbeschikbaar(gekozencat)">
			<AppNavigationItem v-for="mat in MateriaalAr"
				@click="beschikbaar(mat.categorie, mat.id, mat.naam)"
				:key="mat.id"
				:title="mat.naam">
				<template slot="actions">
					<ActionButton icon="icon-details"
						@click="meerinfo(mat.omschrijving)">
						{{ mat.omschrijving }}
					</ActionButton>
				</template>
			</AppNavigationItem>
		</AppSidebar>
	</Content>
</template>

<script>
import ActionButton from '@nextcloud/vue/dist/Components/ActionButton'
import AppContent from '@nextcloud/vue/dist/Components/AppContent'
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppNavigationSettings from '@nextcloud/vue/dist/Components/AppNavigationSettings'
import AppNavigationNew from '@nextcloud/vue/dist/Components/AppNavigationNew'
import AppSidebar from '@nextcloud/vue/dist/Components/AppSidebar'
// import UserBubble from '@nextcloud/vue/dist/Components/UserBubble'
// import Actions from '@nextcloud/vue/dist/Components/Actions'
// import ActionCheckbox from '@nextcloud/vue/dist/Components/ActionCheckbox'
import ActionInput from '@nextcloud/vue/dist/Components/ActionInput'
// import Multiselect from '@nextcloud/vue/dist/Components/Multiselect'
// import DatetimePicker from '@nextcloud/vue/dist/Components/DatetimePicker'
import '@nextcloud/dialogs/styles/toast.scss'
import { generateUrl } from '@nextcloud/router'
import { showError, showSuccess } from '@nextcloud/dialogs'
import axios from '@nextcloud/axios'
// import { emit } from '@nextcloud/event-bus'
// cdimport NcButton from '@nextcloud/vue/dist/Components/NcButton'
// import CheckboxRadioSwitch from '@nextcloud/vue/dist/Components/CheckboxRadioSwitch'

export default {
	name: 'App',
	components: {
		ActionButton,
		AppContent,
		AppNavigation,
		AppSidebar,
		AppNavigationItem,
		AppNavigationSettings,
		AppNavigationNew,
		// UserBubble,
		// DatetimePicker,
		// Actions,
		// ActionCheckbox,
		ActionInput,
		// Multiselect,
		// NcButton,
		// CheckboxRadioSwitch,
	},
	data() {
		return {
			AanvraagAr: [],
			AanvraagPAr: [],
			AanvraagPrivAr: [],
			AanvraagWerkAr: [],
			WerkstaatAr: [],
			KlikbaarAr: [],
			ToekenbaarAr: [],
			BestaandeWLAr: [],
			WatistoegekendAr: [],
			SaldiAr: [],
			PersoneelsInfoAr: [],
			currentNoteId: null,
			gekozenPCategorie: 'nieuwe categorie',
			gekozenJCategorie: '',
			currentPNoteId: null,
			currentWNoteId: null,
			updating: false,
			updateWS: true,
			loading: true,
			show: false,
			magAanmaken: true,
			lengteAr: 1,
			lengteWSAr: 0,
			volgendeNote: false,
			perAanvraag: false,
			isAdmin: false,
			toonTitel: 'Jouw Links',
			emailPL: '',
			formAan: false,
			emailVan: '',
			priveAdmin: false,
			jesAdmin: false,
			uid: '',
			vorigid: null,
			opmerking: '',
			toonOpmerking: false,
			enkelraadplegen: false,
			aanmaken: false,
			extraplaceholderTekst: '',
			uitlegTonen: false,
			Plinks: false,
			Jlinks: false,
			Wlinks: false,
			TKlinks: false,
			TKAlinks: false,
			formLink: '',
			formPW: '',
			JESL: '',
			JESemail: '',
			JESpasw: '',
			JESmagadmin: '',
			werkenvoor: '',
		}
	},
	computed: {
		/**
		 * Return the currently selected note object
		 *
		 * @return {object|null}
		 */
		currentNote() {
			if (this.currentNoteId === null) {
				return null
			}
			return this.AanvraagAr.find((note) => note.id === this.currentNoteId)
		},

		/**
		 * Returns true if a note is selected and its title is not empty
		 *
		 * @return {boolean}
		 */
		savePossible() {
			return this.currentNote
		},

		/**
		 * Return the currently selected note object
		 *
		 * @return {object|null}
		 */
		 currentPNote() {
			if (this.currentPNoteId === null) {
				return null
			}
			return this.AanvraagPrivAr.find((note) => note.id === this.currentPNoteId)
		},

		/**
		 * Return the currently selected note object
		 *
		 * @return {object|null}
		 */
		 currentWNote() {
			if (this.currentWNoteId === null) {
				return null
			}
			return this.AanvraagWerkAr.find((note) => note.id === this.currentWNoteId)
		},

		/**
		 * Returns true if a note is selected and its title is not empty
		 *
		 * @return {boolean}
		 */
		 savePPossible() {
			return this.currentPNote
		},

		/**
		 * Returns true if a note is selected and its title is not empty
		 *
		 * @return {boolean}
		 */
		 saveWPossible() {
			return this.currentWNote
		},

	},
	beforeMount() {
		this.uitconfig()
	},
	methods: {
		close() {
			this.show = false
		},

		/**
		 * Nieuwe jeslink voor iedereen
		 */
		newNote() {
			this.currentNoteId = -1
			this.priveAdmin = false
			this.jesAdmin = true
			this.AanvraagAr.push({
				id: -1,
				wie: this.JESL,
				email: this.JESemail,
				categorie: this.gekozenJCategorie,
				groep: 'iedereen',
			})
			this.$nextTick(() => {
				this.$refs.urlJ.focus()
			})
		},

		cancelJNote() {
			this.AanvraagAr.splice(this.AanvraagAr.findIndex((note) => note.id === -1), 1)
			this.currentoteId = null
		},

		naarWerklink(a, b) {
			this.formAan = true
			this.formLink = a
			this.formPW = b
			this.formEm = this.emailPL
			this.$nextTick(() => {
				this.$refs.wlform.submit()
				setTimeout(function() { this.blanco() }.bind(this), 1000)
			})
		},

		blanco() {
			this.formPW = ''
			this.formAan = false
		},

		/**
		 * Niuwe favorietenlink -> prive
		 */
		newPNote() {
			this.currentPNoteId = -1
			this.priveAdmin = true
			this.Plinks = true
			this.jesAdmin = false
			this.AanvraagPrivAr.push({
				id: -1,
				wie: this.uid,
				email: this.emailPL,
				categorie: this.gekozenPCategorie,
				groep: 'prive',
			})
			this.$nextTick(() => {
				this.$refs.url.focus()
			})
		},

		cancelPNote() {
			this.AanvraagPrivAr.splice(this.AanvraagPrivAr.findIndex((note) => note.id === -1), 1)
			this.currentPNoteId = null
		},

		/**
		 * Nieuwe werklink -> afgeschermd
		**/
		newWNote() {
			this.currentWNoteId = -1
			this.priveAdmin = false
			this.Plinks = false
			this.jesAdmin = false
			this.werkAdmin = true
			this.Wlinks = false
			this.TKlinks = false
			this.TKAlinks = true
			this.AanvraagWerkAr.push({
				id: -1,
				wie: this.JESL,
				email: this.JESemail,
				pasw: this.JESpasw,
				categorie: 'werklink',
				groep: 'afgeschermd',
			})
			this.$nextTick(() => {
				this.$refs.urlW.focus()
			})
		},

		cancelWNote() {
			this.AanvraagWerkAr.splice(this.AanvraagWerkAr.findIndex((note) => note.id === -1), 1)
			this.currentWNoteId = null
		},
		async uitconfig() {
			try {
				const response = await axios.get(generateUrl('/apps/jeslinks/uitconf'))
				this.JESL = response.data.wie
				this.JESpasw = response.data.pasw
				this.JESmagadmin = response.data.admin
				// showSuccess(this.JESL + ' - ' + this.JESpasw)
			} catch (e) {
				console.error(e)
				showError('Kan geen configinfo vinden')
			}
			this.personeelsinfo()
		},

		/**
		 * Action tiggered when clicking the save button
		 * create a new note or save
		 */
		 saveNote() {
			if (this.currentNoteId === -1) {
				this.gekozenJCategorie = this.currentNote.categorie
				this.createNote(this.currentNote, 'J')
			} else {
				this.updateNote(this.currentNote)
			}
		},

		/**
		 * Action tiggered when clicking the save button
		 * create a new note or save
		 */
		 savePNote() {
			if (this.currentPNoteId === -1) {
				this.gekozenPCategorie = this.currentPNote.categorie
				this.createNote(this.currentPNote, 'P')
			} else {
				this.updateNote(this.currentPNote)
			}
		},

		saveWNote() {
			if (this.currentWNoteId === -1) {
				this.createNote(this.currentWNote, 'W')
			} else {
				this.updateNote(this.currentWNote)
			}
		},

		/**
		 * Links opvragen van de gebruiker voor het linkse zijmenu
		 * De eigenaar van JES en werklinks is ingegeven via jes.config.php
		 * @param {object} note Note object
		 */
		 async overzichtLinks() {
			this.updating = true
			this.priveAdmin = false
			this.jesAdmin = false
			try {
				const response = await axios.get(generateUrl('/apps/jeslinks/links'))
				this.AanvraagAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan geen links vinden')
			}
			this.updating = false
			this.overzichtELinks()
		},

		/**
		 * Favorieten opvragen van de gebruiker voor het zijmenu links
		 *
		 * @param {object} note Note object
		 */
		 async overzichtELinks() {
			this.updating = true
			try {
				const response = await axios.get(generateUrl('/apps/jeslinks/links/eigenlinks'))
				this.AanvraagPrivAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan geen links vinden')
			}
			this.updating = false
			// showSuccess('tot hier')
		},

		/**
		 * Links opvragen van de gebruiker voor midden
		 *
		 * @param {object} note Note object
		 */
		 async toonLinks(l, w, s) {
			this.priveAdmin = false
			this.jesAdmin = false
			this.werkAdmin = false
			const categorie = l
			const wie = w
			let groep
			this.updating = true
			if (s === 'jeslinks') {
				this.gekozenJCategorie = categorie
				this.Jlinks = true
				this.Plinks = false
				this.Wlinks = false
				this.TKlinks = false
				groep = 'iedereen'
			}
			if (s === 'persoonlijk') {
				this.gekozenPCategorie = categorie
				this.Jlinks = false
				this.Plinks = true
				this.Wlinks = false
				this.TKlinks = false
				groep = 'prive'
			}
			try {
				const response = await axios.get(generateUrl(`/apps/jeslinks/links/categorie/${categorie}/${wie}/${groep}`))
				this.KlikbaarAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan geen links vinden')
			}
			this.updating = false
		},

		/**
		 * WerkLinks opvragen van de gebruiker voor midden
		 *
		 * @param {object} note Note object
		 */
		 async toonWLinks() {
			const wie = this.uid
			this.updating = true
			this.Jlinks = false
			this.Plinks = false
			this.Wlinks = true
			this.TKlinks = false
			try {
				const response = await axios.get(generateUrl(`/apps/jeslinks/links/werklink/${wie}`))
				this.KlikbaarAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan geen werklinks vinden')
			}
			this.updating = false
		},

		/**
		 * WerkLinks opvragen personeelslid voor controle welke hij/zij heeft
		 *
		 * @param {object} note Note object
		 */
		 async toonWLinksVan(w, e) {
			const wie = w
			this.emailVan = e
			this.werkenvoor = w
			this.updating = true
			this.Jlinks = false
			this.Plinks = false
			this.Wlinks = false
			this.TKlinks = true
			try {
				const response = await axios.get(generateUrl(`/apps/jeslinks/links/werklink/${wie}`))
				this.WatistoegekendAr = response.data
				this.KlikbaarAr = this.ToekenbaarAr
			} catch (e) {
				console.error(e)
				showError('Kan geen werklinks vinden')
			}
			this.updating = false
		},

		nakijken(n, w) {
			if (this.WatistoegekendAr.some(data => data.naam === n)) {
				const eid = this.WatistoegekendAr.find(x => x.naam === n).id
				// showSuccess(eid)
				this.$set(this.KlikbaarAr[w], 'opslagknop', true)
				this.$set(this.KlikbaarAr[w], 'teschrappen', eid)
			} else {
				this.$set(this.KlikbaarAr[w], 'opslagknop', false)
			}
		},

		async LinkBijVoor(rij) {
			const note = { idwl: this.KlikbaarAr[rij].id, wie: this.werkenvoor, email: this.emailVan, url: this.KlikbaarAr[rij].url, naam: this.KlikbaarAr[rij].naam, categorie: this.KlikbaarAr[rij].categorie, pasw: this.KlikbaarAr[rij].pasw, groep: this.KlikbaarAr[rij].groep }
			try {
				await axios.put(generateUrl(`/apps/jeslinks/links/werklinkbij/${note.idwl}`), note)
			} catch (e) {
				console.error(e)
				showError('Kan werklink niet aanmaken')
			}
			this.$nextTick(() => {
				this.toonWLinksVan(this.werkenvoor, this.emailVan)
			})
		},

		/**
		 * WerkLinks opvragen van 'wie' via config: alle afgeschermde werklinks voor midden
		 *
		 * @param {object} note Note object
		 */
		 async toekenbaar() {
			const wie = this.JESL
			try {
				const response = await axios.get(generateUrl(`/apps/jeslinks/links/afgeschermd/${wie}`))
				this.ToekenbaarAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan geen werklinks vinden')
			}
			this.updating = false
		},

		toonConfigWLinks() {
			this.updating = true
			this.Jlinks = false
			this.Plinks = false
			this.Wlinks = false
			this.TKlinks = true
			this.BestaandeWLAr = this.ToekenbaarAr
		},

		/**
		 * Create a new note by sending the information to the server
		 *
		 * @param {object} note Note object
		 */
		 async createNote(note, s) {
			this.updating = true
			let index
			try {
				const response = await axios.post(generateUrl('/apps/jeslinks/links'), note)
				if (s === 'P') {
					index = this.AanvraagPrivAr.findIndex((match) => match.id === this.currentPNoteId)
					this.$set(this.AanvraagPrivAr, index, response.data)
					this.currentPNoteId = response.data.id
					this.toonLinks(this.gekozenPCategorie, this.uid, 'persoonlijk')
				}
				if (s === 'J') {
					index = this.AanvraagAr.findIndex((match) => match.id === this.currentNoteId)
					this.$set(this.AanvraagAr, index, response.data)
					this.currentNoteId = response.data.id
					this.toonLinks(this.gekozenJCategorie, this.JESL, 'jeslinks')
				}
				if (s === 'W') {
					index = this.AanvraagWerkAr.findIndex((match) => match.id === this.currentWNoteId)
					this.$set(this.AanvraagWerkAr, index, response.data)
					this.currentWNoteId = response.data.id
					this.werkAdmin = false
					this.TKAlinks = false
					this.toekenbaar()
					showSuccess('Afgeschermde Link is aangemaakt')
				}
			} catch (e) {
				console.error(e)
				showError('Kan je link niet toevoegen')
			}
			this.updating = false
			this.overzichtLinks()
		},

		/**
		 * Update an existing note on the server
		 *
		 * @param {object} note Note object
		 */
		 async updateNote(note) {
			this.updating = true
			try {
				await axios.put(generateUrl(`/apps/jeslinks/links/${note.id}`), note)
			} catch (e) {
				console.error(e)
				showError('Kan de link niet updaten')
			}
			this.updating = false
		},

		/**
		 * Create a new note and focus the note content field automatically
		 *
		 * @param {object} note Note object
		 */
		 openNote(note) {
			if (this.updating) {
				return
			}
			this.currentNoteId = note.id
			this.$nextTick(() => {
				showSuccess(this.currentNoteId)
			})
		},

		toonAanmaken() {
			this.aanmaken = true
		},

		async perPersoon(p) {
			this.loading = true
			try {
				const response = await axios.get(generateUrl(`/apps/personeelsadmin/personeelsinfo/zoekenP/${p}`), p)
				this.AanvraagPAr = response.data

			} catch (e) {
				console.error(e)
				showError('Kan niemand vinden ...')
			}
			this.loading = false
		},

		/**
	 	* Personeels-extra info ophalen
	 	*/
		async personeelsinfo() {
			try {
				const response = await axios.get(generateUrl('/apps/personeelsadmin/personeelsinfo'))
				this.PersoneelsInfoAr = response.data
				this.emailPL = this.PersoneelsInfoAr[0].emailinvoer
				this.uid = this.PersoneelsInfoAr[0].userId
			} catch (e) {
				console.error(e)
				showError('Kan geen extra personeelsinfo opvragen ...')
			}
			this.lengteAr = this.PersoneelsInfoAr.length
			// this.teamleidersinfo(this.PersoneelsInfoAr[0].teamid)
			this.isPLadmin(this.uid)
			this.overzichtLinks()
			this.toekenbaar()
		},

		/**
	 	* Personeels-extra info: kijken of gebruiker admin is
	 	*/
		 async isPLadmin(w) {
			const wie = w
			const groep = this.JESmagadmin
			try {
				const response = await axios.get(generateUrl(`/apps/personeelsadmin/ingroepen/zoek/${wie}/${groep}`))
				response.data[0] === 'ingroep' ? this.isAdmin = true : this.isAdmin = false
			} catch (e) {
				console.error(e)
				showError('Kan geen admin info opvragen ...')
			}
		},

		/**
	 	* Personeels-extra info (coordinator) ophalen in deze app niet in gebruik
	 	*/
		async teamleidersinfo(tc) {
			try {
				const response = await axios.get(generateUrl(`/apps/personeelsadmin/personeelsinfo/zoekenTC/${tc}`, tc))
				this.TeamleiderInfoAr = response.data
			} catch (e) {
				console.error(e)
				showError('Kan de teamleidder niet vinden ...')
			}
		},

		meerinfo(a) {
			showSuccess(a)
		},

		uitleg() {
			this.uitlegTonen = !this.uitlegTonen
		},

		/**
	 	* Link schrappen
	 	*/
		 async linkWeg(l, w) {
			const id = l
			try {
				const response = await axios.delete(generateUrl(`/apps/jeslinks/links/${id}`))
				showSuccess(response.data[0])
			} catch (e) {
				console.error(e)
				showError('Kan de link niet verwijderen')
			}
			if (w === 'jeslinks') {
				this.toonLinks(this.gekozenJCategorie, 'JES', 'jeslinks')
			}
			if (w === 'persoonlijk') {
				this.toonLinks(this.gekozenPCategorie, this.uid, 'persoonlijk')
			}
			if (w === 'TK') {
				this.toonWLinksVan(this.werkenvoor, this.emailVan)
			}
			// this.overzichtLinks()
		},

		async klembord(abo, e) {
			if (e.shiftKey) {
				window.location.href = abo
			} else {
				try {
					await navigator.clipboard.writeText(abo)
					showSuccess('Link naar klembord gekopieerd')
				} catch ($e) {
					showError('Kon de link niet kopiëren')
				}
			}
		},

		async melden(wa, co) {
			const inJson = { id: wa, coordid: co }
			try {
				await axios.put(generateUrl(`/apps/reserveren/melden/${inJson.id}`), inJson)
			} catch (e) {
				console.error(e)
				showError('Kon de reservatie niet verwijderen')
			}
		},
	},
}
</script>
<style scoped>
#app-content > div {
	width: 100%;
	height: 100%;
	padding: 20px;
	display: flex;
	flex-direction: column;
	flex-grow: 1;
}

input[type='text'] {
	width: 100%;
}

textarea {
	flex-grow: 1;
	width: 100%;
}

.test{
	background-color: aqua;
	width: 50px;
	height: 50px;
	position: relative;
}

h1{
	text-align: center;
	font-size: 22px;
	font-weight: 500;
	margin-top: 50px;
	margin-bottom: 30px;
}

h2{
	font-weight: 500;
	margin-top: 10px;
}

.titelAN{
	margin-left: 10px;
}

h3{
	font-size: 20px;
}

.ics{
	margin-top: 15px;
}

td{
	padding-right: 15px;
}

.extraOpmerking{
	margin-top: 50px;
	border-top: 1px dotted;
	padding-top: 15px;
}

.keuze{
	text-align: center;
}

.in-takenvak{
	padding: 2px 20px 20px 20px;
	border: 1px dotted #999;
	margin-bottom: 10px;
}

.vraagcontainer{
	padding: 10px 50px 10px 50px;
	max-width: 1200px;
	margin: 0 auto 0 auto;
}

.hoger{
	height: 80px;
}

.onder{
	width: 100%;
	display: block;
}

.opzij{
	display: block !important;
	margin-left: 12px;
	position: relative;
}

.praktisch{
	margin-bottom: 4px;
	margin-top: 7px;
}

.waarschuwing{
	color: crimson;
}

.isok{
	color: rgb(25, 160, 137);
}

.opzij > div{
	width: 313px;
	position: relative;
}

.vet{
	font-weight: bold;
}

.totalen{
	text-align: right;
	font-weight: bold;
	padding-right: 5px;
}

.breed100{
	display: block;
	width: 100%;
	border-bottom: 2px solid #999;
	padding-bottom: 10px;
	padding-top: 6px;
	margin-bottom: 2px;
}

.groen{
	border-right: 5px solid #acea56;
}

.rood{
	border-right: 5px solid #ec6840;
}

.filterknop{
	margin-bottom: 20px;
	border-bottom: 0.5px solid #999;
}

#footer{
	font-size: 10px;
}

.uren, .urenc{
	display: inline-block;
	min-width: 85px;
	vertical-align: middle;
}

.urenc{
	text-align: center;
}

.dagaanduiding{
	width: 80px;
	display: inline-block;
	font-size: 12px;
	text-align: right;
	padding-right: 10px;
	vertical-align: top;
	font-weight: bold;
}

.saldo, .saldocl, .verwachtcl{
	display: inline-block;
	padding: 5px 20px 5px 20px;
	margin: 0px -4px 0px;
	min-height: 60px;
	vertical-align: top;
}

.dagc{
	width: 380px;
	padding: 0px;
	background-color: #F0F8FF;
	margin: 0px -4px 0px;
	display: inline-block;
	min-height: 60px;
}

.dag{
	display: block;
	position: relative;
	width: 380px;
	padding: 5px 0px 5px 20px;
	margin: 0px;
	box-sizing: border-box;
}

.avond{
	display: block;
	position: relative;
	background-color: #0068b5;
	width: 377px;
	padding: 5px 0px 5px 20px;
	margin: 10px 0px 0px 0px;
	box-sizing: border-box;
	min-height: 60px;
}

.avondWS{
	display: block;
	position: relative;
	background-color: #0068b5;
	width: 377px;
	padding: 5px 0px 5px 20px;
	margin: 27px 0px 0px 0px;
	box-sizing: border-box;
	min-height: 61px;
	color: #FFF;
}

.verwachtcl{
	background-color: #cfe0a2;
	width: 100px;
}

.saldocl{
	background-color: #a9ddf3;
	width: 100px;
}

.saldocl .uren, .verwachtcl .uren{
	width: 70px;
	margin-top: 7px;
}

.avond input{
	background-color: #f3f3f3;
}

.saldo{
	background-color: #e6f6c3;
	width: 180px;
}

.pauze{
	width: 40px;
	display: inline-block;
}

.werkstaatlijn{
	border-bottom: 0.5px solid #999;
}

.jescode {
	width: 40px;
}

.saldo .multiselect{
	min-width: 50px;
	margin-right: 15px;
}

.saldo .multiselect__single{
	padding: 0px 0px !important;
	color:#ec6840;
}

.breed34{
	width: 34px;
	min-width: 34px;
	height: 10px;
	min-height: 10px;
	background-color: rgba(200,200,200,0);
	border: 0px;
}

.indagaanduiding{
	display: block;
	min-height: 60px;
}

.opslaanknop{
	margin-left: 15px;
	vertical-align: top;
	margin-top: 9px;
}

.dblauw{
	background-color: #0068b5;
	height: 19px;
}

.app-navigation ul{
	height: auto;
}

.fenevknoppen{
	margin-top:20px;
	margin-bottom: 15px;
}

.adminvakje{
	border-bottom: 1px dotted #999;
	padding-bottom: 10px;
}

a:link{
	color: #0068b5;
	font-weight: 700;
	vertical-align: middle;
	display: inline-block;
	width: calc(100% - 100px);
}

a:hover {
	color: #ec6840;
}

.delknopje, .copknopje{
	display: inline-block;
}

.linklijn{
	border-bottom: 1px dotted #999;
	padding: 5px 0px 5px;
	vertical-align: middle;
}

.toevoegknopje{
	vertical-align: middle;
	margin-left: 20px;
}

.h3inline{
	display: inline-block;
	vertical-align: middle;
}

.werklinknav{
	color: #0068b5;
	font-weight: 700;
	vertical-align: middle;
	display: inline-block;
	width: calc(100% - 100px);
}

.werklinknavM{
	color: #0068b5;
	font-weight: 700;
}

.naamlink{
	color: #0068b5;
	font-weight: 700;
	vertical-align: middle;
	display: inline-block;
	width: calc(100% - 190px);
}

.toekenknop{
	width: 180px;
}

.success{
	background-color: #d2e8a5;
	border: #d2e8a5;
}

.success:hover{
	background-color: #eafacb;
}
</style>
