import { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from 'lucide-vue-next';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    image?: string;
    phone?: string;
    birth_date?: string;
    address?: Object;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Post {
    id: number
    title: string
    body: string
    tags: { id: number; name: string }[]
    likes: number
    dislikes: number
    views: number
    comments: {
      id: number
      content: string
      user: { name: string }
    }[]
    user: User
}

export interface Comment {
    id: number;
    body: string;
    likes: number;
    user: User;
}

export type BreadcrumbItemType = BreadcrumbItem;
