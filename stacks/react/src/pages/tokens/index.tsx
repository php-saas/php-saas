import SettingsLayout from '@/layouts/settings/layout';
import { Head, usePage } from '@inertiajs/react';
import Container from '@/components/container';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { DataTable } from '@/components/data-table';
import React from 'react';
import { Token } from '@/types/token';
import { columns } from '@/pages/tokens/components/columns';
import CreateToken from '@/pages/tokens/components/create-token';
import { BreadcrumbItem, PaginatedData } from '@/types';
import { PlusIcon } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Settings',
    href: '/settings',
  },
  {
    title: 'API Tokens',
    href: '/settings/tokens',
  },
];

export default function Tokens() {
  const page = usePage<{
    tokens: PaginatedData<Token>;
  }>();
  return (
    <SettingsLayout breadcrumbs={breadcrumbs}>
      <Head title="API Tokens" />
      <Container className="max-w-5xl">
        <div className="flex items-start justify-between">
          <Heading title="API Tokens" description="Here you can manage API tokens" />
          <div className="flex items-center gap-2">
            <CreateToken>
              <Button>
                <PlusIcon />
                Create token
              </Button>
            </CreateToken>
          </div>
        </div>
        <DataTable columns={columns} paginatedData={page.props.tokens} />
      </Container>
    </SettingsLayout>
  );
}
