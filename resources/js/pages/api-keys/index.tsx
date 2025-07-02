import SettingsLayout from '@/layouts/settings';
import { Head, usePage } from '@inertiajs/react';
import Container from '@/components/container';
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
import { DataTable } from '@/components/data-table';
import React from 'react';
import { ApiKey } from '@/types/api-key';
import { columns } from '@/pages/api-keys/components/columns';
import CreateApiKey from '@/pages/api-keys/components/create-api-key';
import { PaginatedData } from '@/types';

export default function ApiKeys() {
  const page = usePage<{
    apiKeys: PaginatedData<ApiKey>;
  }>();
  return (
    <SettingsLayout>
      <Head title="API Keys" />
      <Container className="max-w-5xl">
        <div className="flex items-start justify-between">
          <Heading title="API Keys" description="Here you can manage API keys" />
          <div className="flex items-center gap-2">
            <a href="/api-docs/index.html" target="_blank">
              <Button variant="outline">Docs</Button>
            </a>
            <CreateApiKey>
              <Button>Create</Button>
            </CreateApiKey>
          </div>
        </div>
        <DataTable columns={columns} paginatedData={page.props.apiKeys} />
      </Container>
    </SettingsLayout>
  );
}
