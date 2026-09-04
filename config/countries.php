<?php

// Mirrors Frontend/src/constants/countries.ts's COUNTRY_NAMES exactly —
// keep the two in sync by hand if either changes (same static-list
// rationale as that file: "no admin-editable use case" for the list of
// countries itself, only for the price attached to one). Used by
// Admin\SubscriptionPriceController's country picker so the value an admin
// sets a price against always matches what a player can actually select
// on the mobile app's country-selection screen.
return [
    'Sri Lanka', 'India', 'Pakistan', 'Bangladesh', 'Nepal', 'Bhutan', 'Maldives', 'Afghanistan',
    'Australia', 'New Zealand', 'England', 'Scotland', 'Wales', 'Ireland', 'South Africa',
    'Zimbabwe', 'Kenya', 'Namibia', 'United Arab Emirates', 'Oman', 'Qatar', 'Saudi Arabia',
    'United States', 'Canada', 'West Indies', 'Jamaica', 'Trinidad and Tobago', 'Barbados',
    'China', 'Japan', 'South Korea', 'Malaysia', 'Singapore', 'Thailand', 'Indonesia',
    'Philippines', 'Vietnam', 'Hong Kong', 'Germany', 'France', 'Italy', 'Spain', 'Portugal',
    'Netherlands', 'Belgium', 'Switzerland', 'Austria', 'Sweden', 'Norway', 'Denmark', 'Finland',
    'Poland', 'Russia', 'Turkey', 'Greece', 'Egypt', 'Nigeria', 'Ghana', 'Brazil', 'Argentina',
    'Other',
];
