<?php
/**
 * Template Name: Event Ticket
 * Version: 1.0.0
 * Description: Clean, professional ticket layout with attendee summary, add-ons, QR link back to the entry, and space for branding.
 * Author: Nyasha Ushewokunze
 * Group: SimplyBiz
 * Required PDF Version: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Locate a field on the form by matching its label or admin label.
 *
 * @param array $form    Gravity Forms form object.
 * @param array $targets Array of label strings to match.
 *
 * @return object|null
 */
function gfwi_et_find_field_by_label( $form, array $targets ) {
	$normalized_targets = array_map(
		function ( $value ) {
			return strtolower( trim( $value ) );
		},
		$targets
	);

	if ( empty( $form['fields'] ) || ! is_array( $form['fields'] ) ) {
		return null;
	}

	foreach ( $form['fields'] as $field ) {
		if ( ! is_object( $field ) || ! isset( $field->id ) ) {
			continue;
		}

		$candidates = array(
			isset( $field->label ) ? strtolower( trim( (string) $field->label ) ) : '',
			isset( $field->adminLabel ) ? strtolower( trim( (string) $field->adminLabel ) ) : '',
		);

		foreach ( $candidates as $candidate ) {
			if ( $candidate && in_array( $candidate, $normalized_targets, true ) ) {
				return $field;
			}
		}
	}

	return null;
}

/**
 * Retrieve a field value by potential labels, falling back to the raw entry value.
 *
 * @param array $form   Gravity Forms form object.
 * @param array $entry  Entry data.
 * @param array $labels Candidate labels to search.
 *
 * @return string|array
 */
function gfwi_et_value_by_labels( $form, $entry, array $labels ) {
	$field = gfwi_et_find_field_by_label( $form, $labels );

	if ( ! $field ) {
		return '';
	}

	$field_id = (string) $field->id;

	if ( class_exists( 'GFCommon' ) && ! empty( $field->label ) ) {
		// Use a merge tag to get the display value (handles choices / inputs nicely).
		$merge_tag = sprintf( '{%s:%d}', $field->label, $field->id );
		$value     = GFCommon::replace_variables( $merge_tag, $form, $entry, false, false, false, 'text' );
	} else {
		$value = rgar( $entry, $field_id );
	}

	return $value;
}

/**
 * Extract add-ons as an array for list display.
 *
 * @param array $form  Gravity Forms form object.
 * @param array $entry Entry data.
 *
 * @return array
 */
function gfwi_et_get_addons( $form, $entry ) {
	$raw = gfwi_et_value_by_labels( $form, $entry, array( 'add-ons', 'addons', 'add on', 'extras', 'options' ) );

	if ( empty( $raw ) ) {
		return array();
	}

	if ( is_array( $raw ) ) {
		return array_filter( array_map( 'trim', $raw ) );
	}

	$parts = preg_split( '/,|\n/', (string) $raw );

	return array_filter(
		array_map(
			'trim',
			$parts
		)
	);
}

$event_name = gfwi_et_value_by_labels( $form, $entry, array( 'event name', 'event', 'event title' ) );
$registrant = gfwi_et_value_by_labels( $form, $entry, array( 'registrant name', 'name', 'full name', 'attendee name' ) );
$attendees  = gfwi_et_value_by_labels( $form, $entry, array( 'number of attendees', 'attendees', 'guests', 'guest count' ) );
$addons     = gfwi_et_get_addons( $form, $entry );
$logo_url   = gfwi_et_value_by_labels( $form, $entry, array( 'company logo', 'logo' ) );
$entry_link = class_exists( 'GFCommon' ) ? GFCommon::replace_variables( '{entry:url}', $form, $entry ) : '';

$qr_url = '';
if ( ! empty( $entry_link ) ) {
	$qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=' . rawurlencode( $entry_link );
}

/**
 * Render a friendly value with fallback text.
 *
 * @param string|array $value     The value to show.
 * @param string       $fallback  Fallback text when missing.
 *
 * @return string
 */
function gfwi_et_display_value( $value, $fallback = 'Not provided' ) {
	if ( is_array( $value ) ) {
		$value = implode( ', ', array_filter( $value ) );
	}

	$value = trim( (string) $value );

	return '' !== $value ? $value : $fallback;
}

?>

<style>
:root {
	--bg: #f6f7fb;
	--card: #ffffff;
	--accent: #0b4ea2;
	--accent-soft: rgba(11, 78, 162, 0.08);
	--text: #1c2430;
	--muted: #5f6b7b;
	--divider: #e3e7ee;
}
body {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	color: var(--text);
}
.ticket-wrap {
	background: var(--bg);
	padding: 28px 24px;
}
.ticket {
	background: var(--card);
	border: 1px solid var(--divider);
	border-radius: 16px;
	box-shadow: 0 16px 48px rgba(17, 31, 64, 0.08);
	overflow: hidden;
}
.ticket__header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 26px 28px 18px;
	border-bottom: 1px dashed var(--divider);
	gap: 18px;
}
.ticket__brand {
	display: flex;
	align-items: center;
	gap: 12px;
}
.ticket__logo {
	width: 72px;
	height: 72px;
	border-radius: 12px;
	background: var(--accent-soft);
	display: grid;
	place-items: center;
	overflow: hidden;
}
.ticket__logo img {
	width: 100%;
	height: 100%;
	object-fit: contain;
}
.ticket__title small {
	font-size: 10px;
	letter-spacing: 0.24em;
	text-transform: uppercase;
	color: var(--muted);
}
.ticket__title h1 {
	margin: 6px 0 0;
	font-size: 26px;
	line-height: 1.2;
}
.ticket__meta {
	padding: 22px 28px 16px;
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 18px;
}
.ticket__item {
	background: var(--accent-soft);
	border-radius: 12px;
	padding: 14px 16px;
}
.ticket__label {
	font-size: 11px;
	text-transform: uppercase;
	letter-spacing: 0.18em;
	color: var(--muted);
	margin-bottom: 6px;
}
.ticket__value {
	font-size: 16px;
	font-weight: 600;
}
.ticket__body {
	display: grid;
	grid-template-columns: 2fr 1fr;
	gap: 22px;
	padding: 6px 28px 26px;
}
.ticket__addons {
	background: #f9fbff;
	border: 1px solid var(--divider);
	border-radius: 12px;
	padding: 16px 18px;
}
.ticket__addons h3 {
	margin: 0 0 10px;
	font-size: 15px;
}
.ticket__addons ul {
	margin: 0;
	padding-left: 18px;
	color: var(--muted);
}
.ticket__addons li {
	margin-bottom: 6px;
}
.ticket__qr {
	display: grid;
	place-items: center;
	background: linear-gradient(145deg, rgba(11, 78, 162, 0.08), rgba(11, 78, 162, 0.02));
	border: 1px solid var(--divider);
	border-radius: 12px;
	padding: 14px;
	text-align: center;
}
.ticket__qr img {
	width: 200px;
	height: 200px;
	object-fit: contain;
}
.ticket__qr small {
	color: var(--muted);
	display: block;
	margin-top: 8px;
}
@media (max-width: 640px) {
	.ticket__body {
		grid-template-columns: 1fr;
	}
	.ticket__header {
		flex-direction: column;
		align-items: flex-start;
	}
}
</style>

<div class="ticket-wrap">
	<div class="ticket">
		<div class="ticket__header">
			<div class="ticket__brand">
				<div class="ticket__logo">
					<?php if ( $logo_url ) : ?>
						<img src="<?php echo esc_url( $logo_url ); ?>" alt="Company logo" />
					<?php else : ?>
						<span style="font-size:12px;color:var(--muted);">Logo</span>
					<?php endif; ?>
				</div>
				<div class="ticket__title">
					<small>Event Ticket</small>
					<h1><?php echo esc_html( gfwi_et_display_value( $event_name, 'Your Event' ) ); ?></h1>
				</div>
			</div>
			<div class="ticket__meta" style="padding:0;max-width:320px;grid-template-columns:1fr;">
				<div class="ticket__item">
					<div class="ticket__label">Entry ID</div>
					<div class="ticket__value">
						<?php echo esc_html( rgar( $entry, 'id', '—' ) ); ?>
					</div>
				</div>
			</div>
		</div>

		<div class="ticket__meta">
			<div class="ticket__item">
				<div class="ticket__label">Registrant</div>
				<div class="ticket__value"><?php echo esc_html( gfwi_et_display_value( $registrant, 'Name pending' ) ); ?></div>
			</div>
			<div class="ticket__item">
				<div class="ticket__label">Attendees</div>
				<div class="ticket__value"><?php echo esc_html( gfwi_et_display_value( $attendees, 'Not specified' ) ); ?></div>
			</div>
		</div>

		<div class="ticket__body">
			<div class="ticket__addons">
				<h3>Add-ons & Extras</h3>
				<?php if ( ! empty( $addons ) ) : ?>
					<ul>
						<?php foreach ( $addons as $addon ) : ?>
							<li><?php echo esc_html( $addon ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<p style="color: var(--muted); margin: 0;">None selected.</p>
				<?php endif; ?>
			</div>

			<div class="ticket__qr">
				<?php if ( $qr_url ) : ?>
					<img src="<?php echo esc_url( $qr_url ); ?>" alt="QR code to entry" />
					<small>Scan to view this entry</small>
				<?php else : ?>
					<small>QR code unavailable (no entry URL).</small>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
